import HeaderFooterTemp from './templates/headerfooter';

export default function getTemplates(templateName : string) {
    switch (templateName) {
        case 'header_footer':
            console.log('HeaderFooterTemp');
            return HeaderFooterTemp;

    }
}
