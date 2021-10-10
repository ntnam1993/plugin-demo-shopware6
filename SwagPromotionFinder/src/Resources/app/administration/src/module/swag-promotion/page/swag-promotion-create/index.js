
Shopware.Component.extend('swag-promotion-create', 'swag-promotion-detail', {
    methods: {
        getPromotion() {
            this.promotion = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            console.log('onClickSave');
            this.isLoading = true;

            this.repository
                .save(this.promotion, Shopware.Context.api)
                .then((respponse) => {
                    console.log(respponse)
                    this.isLoading = false;
                    this.$router.push({ name: 'swag.promotion.detail', params: { id: this.promotion.id } });
                }).catch((exception) => {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$tc('swag-promotion.detail.errorTitle'),
                    message: exception
                });
            });
        }
    }
});
