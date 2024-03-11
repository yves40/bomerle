import $props from '../properties.js'

const $labels = ( () => {
    const labelsfr = {
        interested: 'Vous aimerez aussi',
        emailok: 'Votre demande a été transmise',
        emailko: 'Demande non transmise, réésayez plus tard',
        emailadvice: 'Votre addresse mail ne fera l\'objet d\'aucun traitement\
                        ni d\'aucune mémorisation. \
                        Vous pouvez consulter nos mentions légales \
                        en bas de page pour en savoir plus.'

    }
    const labelsen = {
        interested: 'You may also like',
        emailok: 'email sent',
        emailko: 'email not sent, try later',
        emailadvice: 'Your email address will not be processed in any way\
                        nor any memorization. \
                        You can consult our legal notices\
                        at the bottom of the page to find out more.'
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
