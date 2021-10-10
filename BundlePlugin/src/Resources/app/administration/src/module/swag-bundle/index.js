import deDE from './snippet/de-DE';
import enGB from './snippet/en-GB';
import './page/swag-bundle-list';
import './page/swag-bundle-detail';
import './page/swag-bundle-create';

Shopware.Module.register('swag-bundle', {
    type: 'plugin',
    name: 'Bundles',
    color: '#263fde',
    icon: 'default-shopping-paper-bag-product',
    title: 'swag-bundle.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'swag-bundle-list',
            path: 'list'
        },
        detail: {
            component: 'swag-bundle-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'swag.bundle.list'
            }
        },
        create: {
            component: 'swag-bundle-create',
            path: 'create',
            meta: {
                parentPath: 'swag.bundle.list'
            }
        },
    },

    navigation: [{
        label: 'swag-bundle.general.mainMenuItemGeneral',
        color: '#263fde',
        path: 'swag.bundle.list',
        icon: 'default-shopping-paper-bag-product',
        position: 100
    }]
});
