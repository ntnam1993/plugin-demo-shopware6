
Shopware.Component.extend('swag-bundle-create', 'swag-bundle-detail', {
    methods: {
        getBundle() {
            this.bundle = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;
            console.log(this.repository);

            this.repository
                .save(this.bundle, Shopware.Context.api)
                .then((respponse) => {
                    this.isLoading = false;
                    this.$router.push({ name: 'swag.bundle.detail', params: { id: this.bundle.id } });
                }).catch((exception) => {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$tc('swag-bundle.detail.errorTitle'),
                    message: exception
                });
            });
        }
    }
});
