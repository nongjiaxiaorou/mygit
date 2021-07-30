import request from '../utils/request';

let commonUrl = "http://localhost:8081/tongxin/tx_admin/src/pc";
let commonImgUrl = "http://localhost:8081/tongxin/tx_admin/src/images";
// let commonUrl = "http://112.74.34.150:888/tx-admin/src/pc";
// let commonImgUrl = "http://112.74.34.150:888/tx-admin/src/images";

let baseUrl = {
    commonUrl,
    commonImgUrl
}

export default baseUrl

export const fetchData = query => { // 获取数据库
    return request({
        url: './table.json',
        method: 'get',
        params: query
    });
};