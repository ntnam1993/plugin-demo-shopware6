import './page/swag-promotion-list';
import './page/swag-promotion-detail';
import './page/swag-promotion-create';
import enGB from './snippet/en-GB.json';
import deDE from './snippet/de-DE.json';

const { Module } = Shopware;

Module.register('swag-promotion', {
    type: 'plugin',
    name: 'swag-promotion.general.name',
    title: 'swag-promotion.general.title',
    description: 'swag-promotion.general.description',
    color: "#ca0b74",
    icon: 'default-package-gift',

    snippet: {
        'en-GB': enGB,
        'de-De': deDE
    },

    routes: {
        list: {
            component: 'swag-promotion-list',
            path: 'list'
        },
        detail: {
            component: 'swag-promotion-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'swag.promotion.list'
            }
        },
        create: {
            component: 'swag-promotion-create',
            path: 'create',
            meta: {
                parentPath: 'swag.promotion.list'
            }
        },
    },

    navigation: [{
        label: 'swag-promotion.general.name',
        color: '#ca0b74',
        path: 'swag.promotion.list',
        icon: 'default-package-gift',
        position: 130,
    }]
})
