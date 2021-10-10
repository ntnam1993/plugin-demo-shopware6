import template from './swag-promotion-list.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('swag-promotion-list', {
    template: template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            repository: null,
            promotions: null
        };
    },
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    created() {
        this.repository = this.repositoryFactory.create('swag_promotion');

        console.log('before call api: ', this.repository)
        this.repository
            .search(new Criteria(), Shopware.Context.api)
            .then((result) => {
                this.promotions = result;
                console.log('result from api: ', this.promotions);
            });
    },
    computed: {
        columns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                label: this.$tc('swag-promotion.list.columnName'),
                routerLink: 'swag.promotion.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'discountRate',
                dataIndex: 'discountRate',
                label: this.$tc('swag-promotion.list.columnDiscountRate'),
                inlineEdit: 'number',
                allowResize: true
            }, {
                property: 'startDate',
                dataIndex: 'startDate',
                label: this.$tc('swag-promotion.list.columnStartDate'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'expiredDate',
                dataIndex: 'expiredDate',
                label: this.$tc('swag-promotion.list.columnExpiredDate'),
                inlineEdit: 'string',
                allowResize: true
            }];
        }
    },
});
