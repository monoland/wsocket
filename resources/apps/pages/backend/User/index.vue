<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #toolbar-default>
            <v-btn-tips @click="openProject" label="PROJECT" icon="photo_filter" :show="!disabled.link" />
        </template>

        <v-desktop-table v-if="desktop"
            :single="single"
        ></v-desktop-table>

        <v-mobile-table icon="perm_identity" v-else>
            <template v-slot:default="{ item }">
                <v-list-item-content>
                    <v-list-item-title>{{ item.name }}</v-list-item-title>
                    <v-list-item-subtitle class="f-nunito">{{ item.email }}</v-list-item-subtitle>
                </v-list-item-content>
            </template>
        </v-mobile-table>

        <v-page-form small>
            <v-row>
                <v-col cols="12">
                    <v-text-field
                        label="Nama Operator"
                        :color="$root.theme"
                        v-model="record.name"
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Email Operator"
                        :color="$root.theme"
                        v-model="record.email"
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-select
                        label="Otentikasi"
                        :items="authents"
                        :color="$root.theme"
                        v-model="record.authent_id"
                    ></v-select>
                </v-col>
            </v-row>
        </v-page-form>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';

export default {
    name: 'page-operator',

    mixins: [pageMixins],

    route: [
        { path: 'operator', name: 'operator', root: 'monoland' },
    ],

    data:() => ({
        single: false,
        authents: [
            { text: 'Administrator', value: 1 },
            { text: 'Operator', value: 2 }
        ]
    }),

    created() {
        this.tableHeaders([
            { text: 'Name', value: 'name' },
            { text: 'Email', value: 'email' },
            { text: 'Otentikasi', value: 'authent_name' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Operator',
        });

        this.dataUrl(`/api/users`);

        this.setRecord({
            id: null,
            name: null,
            email: null,
            authent_id: null
        });
    },

    methods: {
        openProject: function() {
            this.$router.push({ name: 'user-project', params: { operator: this.record.id } });
        }
    }
};
</script>