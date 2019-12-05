<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #toolbar-default>
            <v-btn-tips @click="openConsole" label="CONSOLE" icon="photo_filter" :show="!disabled.link" />
        </template>

        <v-desktop-table v-if="desktop"
            single
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
                        label="Nama Project"
                        :color="$root.theme"
                        v-model="record.name"
                        hide-details
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-switch
                        label="Support a client-to-client messages"
                        v-model="record.messages"
                        hide-details
                    ></v-switch>
                </v-col>

                <v-col cols="6">
                    <v-switch
                        label="Enable statistics data"
                        v-model="record.statistics"
                        hide-details
                    ></v-switch>
                </v-col>
            </v-row>
        </v-page-form>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';

export default {
    name: 'page-project',

    mixins: [pageMixins],

    route: [
        { path: 'project', name: 'project', root: 'monoland' },
    ],

    data:() => ({
        // 
    }),

    created() {
        this.tableHeaders([
            { text: 'Name', value: 'name' },
            { text: 'Key', value: 'key' },
            { text: 'Secret', value: 'secret' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Project',
        });

        this.dataUrl(`/api/project`);

        this.setRecord({
            id: null,
            name: null,
            key: null,
            secret: null,
            messages: false,
            statistics: false
        });
    },

    methods: {
        openConsole: function() {
            this.$router.push({ name: 'console', params: { project: this.record.id } });
        }
    }
};
</script>