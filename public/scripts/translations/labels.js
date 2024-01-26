import $props from '../properties.js'

const $labels = ( () => {
    const labelsfr = {
        interested: 'Vous aimerez aussi'
    }
    const labelsen = {
        interested: 'You may also like'
    }
    
    return {
        get: (labelcode) => {
            if($props.get('applang') === 'fr') {
                return labelsfr[labelcode]; 
            }
            else {
                return labelsen[labelcode]; 
            }
        },
    }
})();
export default $labels;
