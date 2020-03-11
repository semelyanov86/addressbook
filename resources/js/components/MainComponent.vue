<template>
    <v-app>
        <v-content>
            <v-container fluid>
                <v-data-table
                    :headers="headers"
                    :items="entries"
                    :items-per-page="5"
                    :loading="loading"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :multi-sort="false"
                    class="elevation-1"
                ></v-data-table>
                <div class="text-center pt-2">
                    <v-btn color="primary" @click="loadMore" :disabled="isDisabled">Load More</v-btn>
                </div>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        data: () => ({
            headers: [],
            entries: [],
            loading: true,
            sortBy: null,
            sortDesc: false,
            number: 2,
            count: null,
            initialized: true
        }),
        methods: {
            getEntries() {
                const self = this;
                this.loading = true;
                const params = {};
                if (this.sortBy) {
                    params.sortby = this.sortBy;
                    params.sortdesc = this.sortDesc;
                }
                params.limit = this.number;
                axios
                    .get('/api/', {params: params})
                    .then(response => {
                        this.entries = response.data.data;
                        this.count = response.data.count;
                    })
                    .catch(error => console.error(error))
                    .finally(function(response) {
                        self.initialized = true;
                        self.loading = false;
                    });
            },
            loadMore() {
                this.number += 2;
                this.getEntries();
            }
        },
        computed: {
            isDisabled() {
                return this.number >= this.count;
            }
        },
        mounted() {
            const self = this;
            this.loading = true;
            axios
                .get('/api/headers')
                .then(response => (this.headers = response.data))
            .catch(error => console.error(error))
                .finally(function(response) {
                    self.loading = false;
                });
            this.getEntries();
        },
        watch: {
            sortBy: function(val) {
                if (this.initialized) {
                    this.getEntries();
                    this.initialized = false;
                }
            },
            sortDesc: function(val) {
                if (this.initialized) {
                    this.getEntries();
                    this.initialized = false;
                }
            }
        }
    }
</script>
