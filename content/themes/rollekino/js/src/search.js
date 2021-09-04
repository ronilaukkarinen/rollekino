/*
* @Author: Timi Wahalahti
* @Date:   2021-06-11 13:38:21
 * @Last Modified by: Niku Hietanen
 * @Last Modified time: 2021-06-22 10:32:19
*/

import initSearch from './apps/search/app';

const targetId = 'search';
const target = targetId ? document.getElementById(targetId) : false;

if (target) {
  initSearch(target);
}
