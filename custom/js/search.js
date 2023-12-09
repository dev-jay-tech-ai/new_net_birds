function getUrlParams() {
  const params = {};
  window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
  function(str, key, value) {
    params[key] = value;
  });
  return params;
}
const pathname = window.location.pathname;
const search = window.location.search;
const btn_search = document.querySelector('#btn_search');
btn_search.addEventListener('click', (e) => {
  e.preventDefault();
  const sn = document.querySelector('#sn');
  const sf = document.querySelector('#sf');
  if(sf.value == '') {
    self.location.href='.'+pathname+search;
    return false;
  }
  //let params = getUrlParams();
  // const search = window.location.search;
  self.location.href='.'+pathname+search+'&sn=' + sn.value + '&sf=' + sf.value;
})