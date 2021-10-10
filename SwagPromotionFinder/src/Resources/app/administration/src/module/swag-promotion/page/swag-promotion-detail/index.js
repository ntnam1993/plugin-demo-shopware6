import template from './swag-promotion-detail.html.twig';

const { Component, Mixin } = Shopware;

Component.register('swag-promotion-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            promotion: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    computed: {
        options() {
            return [
                { value: 'discountRate', name: this.$tc('swag-promotion.detail.discountRateLabel') },
            ];
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('swag_promotion');
        this.getPromotion();
    },

    methods: {
        getPromotion() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.promotion = entity;
                    console.log('this.promotion: ', this.promotion)
                });
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.promotion, Shopware.Context.api)
                .then(() => {
                    this.getPromotion();
                    this.isLoading = false;
                    this.processSuccess = true;
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('swag-promotion.detail.errorTitle'),
                    message: exception
                });
            });
        },

        saveFinish() {
            this.processSuccess = false;
        },
    }
});
