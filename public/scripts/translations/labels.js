import $props from '../properties.js'

const $labels = ( () => {
    const labelsfr = {
        interested: 'Vous aimerez aussi',
        emailok: 'Votre demande a été transmise',
        emailko: 'Demande non transmise, réésayez plus tard'
    }
    const labelsen = {
        interested: 'You may also like',
        emailok: 'email sent',
        emailko: 'email not sent, try later'
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
