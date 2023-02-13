//----------------------------------------------------------------------------
//    properties.js
//
//    Dec 26 2022 Initial
//    Jan 30 2023 Knife images location
//    Feb 04 2023 WIP on knife images administration
//    Feb 13 2023 Image loading delay removed
//----------------------------------------------------------------------------
const $props = ( () => {
  const allprops = {
    version : 'bomerle:1.09, Feb 13 2023 ',
    copyright:  'Ratoon software Corporation Inc, Chabreloche France ',
    imagehandler: 'images.js Feb 13 2023, 1.25 ',
    knifehandler: 'knife.js Feb 08 2023, 1.05 ',
    knivesimageslocation: '%kernel.project_dir%/public/images/knife'
  }
  let dynprops = {
    'imageloadcount' :0,
    'imageavgloadtime' :0
  }
  return {
    version: () => { return allprops.version; },
    copyright: () => { return allprops.copyright; },
    imagehandler: () => { return allprops.imagehandler; },
    knifehandler: () => { return allprops.knifehandler; },
    knivesimageslocation: () => { return allprops.knivesimageslocation; },
    imageavgloadtime: () => { 
        console.log(dynprops);
        return dynprops['imageavgloadtime']; 
    },
    imageloadcount: () => { return dynprops['imageloadcount']; },
    set: (propertyname, value) => { dynprops[propertyname] = value; },
    get: (propertyname) => { return dynprops[propertyname]; },
    save: () => { sessionStorage.setItem('dynprops', JSON.stringify(dynprops)); },
    load: () => {
      let savedprops = sessionStorage.getItem("dynprops");
      if( savedprops !== null) {
        dynprops = JSON.parse(savedprops);
      }
      else {
        sessionStorage.setItem('dynprops', JSON.stringify(dynprops));
        return dynprops;
      }
     }
  }
})();
//  localStorage.setItem('lastlogin', 'yves773340');
