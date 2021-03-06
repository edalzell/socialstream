<template>
    <jet-action-section>
        <template #title>
            Connected Accounts
        </template>

        <template #description>
            Manage and remove your connect accounts.
        </template>

        <template #content>
            <h3 class="text-lg font-medium text-gray-900" v-if="$page.socialstream.connectedAccounts.length === 0">
                You have no connected accounts.
            </h3>
            <h3 class="text-lg font-medium text-gray-900" v-else>
                Your connected accounts.
            </h3>

            <div class="mt-3 ax-w-xl text-sm text-gray-600">
                You are free to connect any social accounts to your profile and may remove any connected accounts at any time. If you feel any of your connected accounts have been compromised, you should disconnect them immediately and change your password.
            </div>

            <div class="mt-5 space-y-6">
                <div v-for="(provider) in $page.socialstream.providers" :key="provider">
                    <connected-account v-if="hasAccountForProvider(provider)" :provider="getAccountForProvider(provider).provider_name" :created-at="getAccountForProvider(provider).created_at">
                        <template #action>
                            <jet-button @click.native="confirmRemove(getAccountForProvider(provider).id)" v-if="$page.socialstream.canRemoveAccounts || $page.socialstream.hasPassword">
                                Remove
                            </jet-button>
                        </template>
                    </connected-account>

                    <connected-account v-else-if="$page.socialstream.show" :provider="provider">
                        <template #action>
                            <action-link :href="route('oauth.redirect', {provider})">
                                Connect
                            </action-link>
                        </template>
                    </connected-account>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <jet-dialog-modal :show="confirmingRemove" @close="confirmingRemove = false">
                <template #title>
                    Remove Connected Account
                </template>

                <template #content>
                    Please confirm your removal of this account - this action cannot be undone.
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingRemove = false">
                        Nevermind
                    </jet-secondary-button>

                    <jet-button class="ml-2"
                                @click.native="removeConnectedAccount(accountId)"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Remove Connected Account
                    </jet-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage';
    import JetActionSection from '@/Jetstream/ActionSection';
    import JetButton from '@/Jetstream/Button';
    import JetDialogModal from '@/Jetstream/DialogModal';
    import JetInput from '@/Jetstream/Input';
    import JetInputError from '@/Jetstream/InputError';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton';
    import ConnectedAccount from '@/Socialstream/ConnectedAccount';
    import ActionLink from '@/Socialstream/ActionLink';

    export default {
        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
            ConnectedAccount,
            ActionLink,
        },

        data() {
            return {
                confirmingRemove: false,
                accountId: null,

                form: this.$inertia.form({
                    '_method': 'DELETE',
                }, {
                    bag: 'removeConnectedAccount'
                })
            };
        },

        methods: {
            confirmRemove(id) {
                this.form.password = '';

                this.accountId = id;

                this.confirmingRemove = true;

                setTimeout(() => {
                    this.$refs.password.focus();
                }, 250);
            },

            hasAccountForProvider(provider) {
                return this.$page.socialstream.connectedAccounts.filter(account => account.provider_name === provider).length > 0;
            },

            getAccountForProvider(provider) {
                if (this.hasAccountForProvider(provider)) {
                    return this.$page.socialstream.connectedAccounts.filter(account => account.provider_name === provider).shift();
                }

                return null;
            },

            getAccountForProvider(provider) {
                const account = this.$page.socialstream.connectedAccounts.filter(account => account.provider_name === provider).shift();

                if (account !== undefined) {
                    return account;
                }

                return null;
            },

            removeConnectedAccount(id) {
                this.form.post(route('connected-accounts.destroy', {id}), {
                    preserveScroll: true,
                    onSuccess: () => {
                        if (! this.form.hasErrors()) {
                            this.confirmingRemove = false;
                        }
                    },
                });
            },
        }
    }
</script>
