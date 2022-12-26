//----------------------------------------------------------------------------
//    properties.js
//
//    Dec 26 2022 Initial
//----------------------------------------------------------------------------
const allprops = {
  "version": 'bomerle:1.51, Dec 26 2022 ',
  "copyright": 'Ratoon software Inc, Chabreloche Corporation'
}
let dynamic = {
  "imageloadingdelay" : 1000,
  "imageloadcount" : 0,
}
// ------------------------------------------------------------------
// Accessor for global properties
// ------------------------------------------------------------------
function getStaticProperty(propertyname) {
  return allprops[propertyname] === undefined ? 
      `${propertyname} : unknown property` : allprops[propertyname];
}
// ------------------------------------------------------------------
// Accessors for dynamic properties
// ------------------------------------------------------------------
function get(propertyname) {
  return dynamic[propertyname] === undefined ? 
      `${propertyname} : unknown property` : dynamic[propertyname];
}
function set(propertyname, value) {
  
}
