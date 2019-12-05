<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #navigate>
            <v-btn-tips @click="$router.go(-1)" label="project" icon="arrow_back" :show="true" />
        </template>

        <v-card flat>
            <v-card-text>
                <h4>Realtime Statistics</h4>
                <div id="statisticsChart" style="width: 100%; height: 250px;"></div>

                <h4>Events</h4>
                <v-simple-table id="events">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Socket</th>
                                <th>Details</th>
                                <th>Time</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(log, idx) in logs.slice().reverse()" :key="idx">
                                <td><span class="badge" :class="getBadgeClass(log)">@{{ log.type }}</span></td>
                                <td>{{ log.socketId }}</td>
                                <td>{{ log.details }}</td>
                                <td>{{ log.time }}</td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-card-text>
        </v-card>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';
import Pusher from 'pusher-js';
import Plotly from 'plotly.js-dist';

export default {
    name: 'page-console',

    mixins: [pageMixins],



    route: [
        { path: 'project/:project/console', name: 'console', root: 'monoland' },
    ],

    data:() => ({
        connected: false,
        chart: null,
        pusher: null,
        app: null,
        port: null,
        apps: null,
        xform: {
            channel: null,
            event: null,
            data: null
        },
        logs: [],
    }),

    created() {
        this.pageInfo({
            icon: 'people',
            title: 'Console',
        });

        this.fetchApp();
    },

    beforeDestroy() {
        this.disconnect();
    },

    methods: {
        fetchApp: async function() {
            try {
                let { data } = await this.http.get(`api/project/${this.$route.params.project}`);

                this.app = data;

                this.connect()
            } catch (error) {
                this.$store.dispatch('errors', error);
            }
        },

        connect() {
            this.pusher = new Pusher(this.app.key, {
                wsHost: this.app.host === null ? window.location.hostname : 'wsocket.loc',
                wsPort: this.port === null ? 6001 : this.port,
                wssPort: this.port === null ? 6001 : this.port,
                wsPath: this.app.path === null ? '' : this.app.path,
                disableStats: true,
                authEndpoint: '/wsc/auth',
                auth: {
                    headers: {
                        'X-CSRF-Token': this.auth.csrf,
                        'X-App-ID': this.app.id
                    }
                },
                enabledTransports: ['ws', 'flash']
            });

            this.pusher.connection.bind('state_change', states => {
                console.log(states.current);
                // $('div#status').text("Channels current state is " + states.current);
            });

            this.pusher.connection.bind('connected', () => {
                this.connected = true;

                this.loadChart();
            });

            this.pusher.connection.bind('disconnected', () => {
                this.connected = false;
                this.logs = [];
            });

            this.pusher.connection.bind('error', event => {
                if (event.error.data.code === 4100) {
                    // $('div#status').text("Maximum connection limit exceeded!");
                    this.connected = false;
                    this.logs = [];
                    // throw new Error("Over capacity");
                }
            });

            this.subscribeToAllChannels();

            this.subscribeToStatistics();
        },

        disconnect() {
            this.pusher.disconnect();
        },

        loadChart: async function() {
            try {
                let { data } = await this.http.get(`/api/${this.app.id}/statistics`);
                
                let chartData = [
                    {
                        x: data.peak_connections.x,
                        y: data.peak_connections.y,
                        type: 'lines',
                        name: '# Peak Connections'
                    },
                    {
                        x: data.websocket_message_count.x,
                        y: data.websocket_message_count.y,
                        type: 'bar',
                        name: '# Websocket Messages'
                    },
                    {
                        x: data.api_message_count.x,
                        y: data.api_message_count.y,
                        type: 'bar',
                        name: '# API Messages'
                    }
                ];
                
                let layout = {
                    margin: {
                        l: 50,
                        r: 0,
                        b: 50,
                        t: 50,
                        pad: 4
                    }
                };

                this.chart = Plotly.newPlot('statisticsChart', chartData, layout);

            } catch (error) {
                this.$store.dispatch('errors', error);
            }
        },

        subscribeToAllChannels() {
            [
                'disconnection',
                'connection',
                'vacated',
                'occupied',
                'subscribed',
                'client-message',
                'api-message',
            ].forEach(channelName => this.subscribeToChannel(channelName))
        },

        subscribeToChannel(channel) {
            this.pusher.subscribe('private-wsc-' + channel)
                .bind('log-message', (data) => {
                    this.logs.push(data);
                });
        },

        subscribeToStatistics() {
            this.pusher.subscribe('private-wsc-statistics')
                .bind('statistics-updated', (data) => {
                        var update = {
                            x:  [[data.time], [data.time], [data.time]],
                            y: [[data.peak_connection_count], [data.websocket_message_count], [data.api_message_count]]
                        };

                        Plotly.extendTraces('statisticsChart', update, [0, 1, 2]);
                });
        },

        getBadgeClass(log) {
            if (log.type === 'occupied' || log.type === 'connection') {
                return 'badge-primary';
            }
            if (log.type === 'vacated') {
                return 'badge-warning';
            }
            if (log.type === 'disconnection') {
                return 'badge-error';
            }
            if (log.type === 'api_message') {
                return 'badge-info';
            }
            return 'badge-secondary';
        },

    //     sendEvent() {
    //         $.post('/{{ request()->path() }}/event', {
    //             _token: '{{ csrf_token() }}',
    //             key: this.app.key,
    //             secret: this.app.secret,
    //             appId: this.app.id,
    //             channel: this.form.channel,
    //             event: this.form.event,
    //             data: this.form.data,
    //         }).fail(() => {
    //             alert('Error sending event.');
    //         });
    //     }
    }
};
</script>