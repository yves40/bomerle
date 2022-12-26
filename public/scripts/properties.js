//----------------------------------------------------------------------------
//    properties.js
//
//    Dec 26 2022 Initial
//----------------------------------------------------------------------------
const $props = ( () => {
  const allprops = {
    version : 'bomerle:1.00, Dec 26 2022 ',
    copyright:  'Ratoon software Corporation Inc, Chabreloche France ',
    imagehandler: 'images.js Dec 26 2022, 1.22',
  }
  let dynprops = {
    'imageloadingdelay' : 400,
    'imageloadcount' :0
  }
dynprops
  return {
    version: () => { return allprops.version; },
    copyright: () => { return allprops.copyright; },
    imagehandler: () => { return allprops.imagehandler; },
    imageloadingdelay: () => { return dynprops['imageloadingdelay']; },
    imageloadcount: () => { return dynprops['imageloadcount']; },
    set: (propertyname, value) => { dynprops[propertyname] = value; },
    get: (propertyname) => { return dynprops[propertyname]; },
    save: () => { sessionStorage.setItem('dynprops', JSON.stringify(dynprops)); },
    load: () => {
      let savedprops = sessionStorage.getItem("dynprops");
      if( savedprops !== null) {
        dynprops = JSON.parse(savedprops);
      }
     }
  }
})();

//  localStorage.setItem('lastlogin', 'yves773340');
