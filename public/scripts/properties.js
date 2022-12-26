//----------------------------------------------------------------------------
//    properties.js
//
//    Dec 26 2022 Initial
//----------------------------------------------------------------------------
const $props = ( () => {
  let allprops = {
    version : 'bomerle:1.00, Dec 26 2022 ',
    copyright:  'Ratoon software Corporation Inc, Chabreloche France ',
    imagehandler: 'images.js Dec 26 2022, 1.21',
    'imageloadingdelay' : 1000,
    'imageloadcount' :0
  }

  return {
    version: () => { return allprops.version; },
    copyright: () => { return allprops.copyright; },
    imageloadingdelay: () => { return allprops['imageloadingdelay']; },
    imageloadcount: () => { return allprops['imageloadcount']; },
    imagehandler: () => { return allprops.imagehandler; },
    set: (propertyname, value) => { allprops[propertyname] = value; },
    get: (propertyname) => { return allprops[propertyname]; },
    save: () => { sessionStorage.setItem('allprops', JSON.stringify(allprops)); },
    load: () => {
      let savedprops = sessionStorage.getItem("allprops");
      if( savedprops !== null) {
        allprops = JSON.parse(savedprops);
      }
     }
  }
})();
