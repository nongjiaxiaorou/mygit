const on = (function() {
  if (document.addEventListener) {
    return function(element, event, handler) {
      if (element && event && handler) {
        element.addEventListener(event, handler, false)
      }
    }
  } else {
    return function(element, event, handler) {
      if (element && event && handler) {
        element.attachEvent('on' + event, handler)
      }
    }
  }
})()

function touchstart(e, el) {
  let touches = e.touches[0]
  let tapObj = el.tapObj
  tapObj.pageX = touches.pageX
  tapObj.pageY = touches.pageY
  tapObj.clientX = touches.clientX
  tapObj.clientY = touches.clientY
  el.time = +new Date()
}
function touchend(e, el) {
  let touches = e.changedTouches[0]
  let tapObj = el.tapObj
  el.time = +new Date() - el.time
  el.endTime = ''
  tapObj.distanceX = tapObj.pageX - touches.pageX
  tapObj.distanceY = tapObj.pageY - touches.pageY
  let isLongTap = el.time > 500 && Math.abs(tapObj.distanceX) < 10 && Math.abs(tapObj.distanceY) < 10
  if (!isLongTap || el.endTime) return
  el.documentHandler(e)
}

export default {
  bind(el, binding, vnode) {
    on(el, 'touchstart', e => {
      touchstart(e, el)
    })
    on(el, 'touchend', e => {
      touchend(e, el)
    })
    el.tapObj = {}
    el.bindingFn = binding.value
    el.documentHandler = function(e) {
      el.bindingFn(el)
    }
  },
  update(el, binding, vnode) {
  },
  unbind(el) {
  }
}