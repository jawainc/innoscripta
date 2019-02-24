<template>
    <v-container>
        <v-layout row>
            <v-flex xs-12>
                <v-toolbar color="primary" dark>
                    <v-btn outline color="white" @click="newCompany">
                        Add New
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-menu offset-y>
                        <v-btn
                                slot="activator"
                                color="primary"
                                dark
                        >
                            {{search.month}}
                        </v-btn>
                        <v-list>
                            <v-list-tile
                                    v-for="(item, index) in searchMonths"
                                    :key="index"
                                    @click="search.month=item.title"
                            >
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>

                    <v-menu offset-y>
                        <v-btn
                                slot="activator"
                                color="primary"
                                dark
                        >
                            {{search.year}}
                        </v-btn>
                        <v-list>
                            <v-list-tile
                                    v-for="(item, index) in searchYears"
                                    :key="index"
                                    @click="search.year=item.title"
                            >
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>

                    <v-btn offset-y color="primary" @click="fetchAll">All</v-btn>

                    <v-text-field
                            append-icon="mdi-search"
                            color="white"
                            label="Search"
                            single-line
                            hide-details
                            class="ml-2 mr-2"
                            @input.native="doSearch"
                    ></v-text-field>

                </v-toolbar>
                <v-data-table
                        :headers="headers"
                        :items="bills"
                        class="elevation-1"
                        :loading="loading"
                >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-center">
                            <v-btn flat icon color="light-blue" @click="edit(props.item.company.id)">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn flat icon color="red" @click="removeBill(props.item.id)">
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </td>
                        <td>{{ props.item.date }}</td>
                        <td><h5>{{ props.item.company.name }}</h5>
                            <small>{{ props.item.company.customerNumber }}</small>
                        </td>
                        <td><h5>{{ props.item.purpose }}</h5>
                            <small>{{ props.item.billNumber }}</small>
                        </td>
                        <td>{{ props.item.amount | currency('€', 2, { symbolOnLeft: false, spaceBetweenAmountAndSymbol:
                            true }) }}
                        </td>

                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>
        <company-add :open-add="openAddNewForm" :id="editCompanyId" v-on:close="handleAddNewForm"></company-add>
        <v-snackbar
                v-model="snackbar"
                :color="snackBarColor"
        >
            {{ snackBarText }}
            <v-btn
                    dark
                    flat
                    @click="snackbar = false"
            >
                Close
            </v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    export default {
        name: 'home-page',
        data: function () {
            return {
                snackbar: false,
                snackBarText: '',
                snackBarColor: 'red',
                openAddNewForm: false,
                editCompanyId: null,
                loading: false,
                searchData: '',
                search: {
                    month: 'All Months',
                    year: '2019',
                },
                headers: [
                    {
                        text: 'Actions',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Date',
                        align: 'left',
                        sortable: true,
                        value: 'date'
                    },
                    {text: 'Customer', value: 'name', sortable: false},
                    {text: 'Bill', value: 'purpose', sortable: false},
                    {text: 'Amount', value: 'amount', sortable: true}
                ],
                bills: [],
                dataStore: [],
                searchMonths: [
                    {title: 'All Months'},
                    {title: 'January'},
                    {title: 'February'},
                    {title: 'March'},
                    {title: 'April'},
                    {title: 'May'},
                    {title: 'June'},
                    {title: 'July'},
                    {title: 'August'},
                    {title: 'September'},
                    {title: 'October'},
                    {title: 'November'},
                    {title: 'December'},
                ],
                searchYears: [
                    {title: '2019'},
                    {title: '2018'},
                    {title: '2017'},
                    {title: '2016'},
                    {title: '2015'}
                ]
            }
        },
        watch: {
            'search.year': function (oldVal, newVal) {
                this.remoteSearch();
            },
            'search.month': function (oldVal, newVal) {
                this.remoteSearch();
            }
        },
        mounted() {
            this.fetchAll();
        },
        methods: {
            /**
             * search DB for invoices
             */
            remoteSearch() {
                this.loading = true;
                axios.post('/api/search', this.search).then((results) => {
                    this.bills = results.data.data;
                    this.dataStore = results.data.data;
                })
                    .finally(() => {
                        this.loading = false;
                    })
            },

            /**
             * filter fetched data
             * @param e
             */
            doSearch(e) {
                let value = e.target.value;
                this.bills = [];
                if (_.isEmpty(value)) {
                    this.bills = this.dataStore;
                    return;
                }
                this.bills = _.filter(this.dataStore, (obj) => {
                    let ret = false;
                    _.forEach(obj, (val, key) => {
                        if (key !== 'id' && key !== 'company') {
                            if (!ret && _.includes(_.lowerCase(val), _.lowerCase(value)))
                                ret = true;
                        } else if (!ret && key === 'company') {
                            if (_.includes(_.lowerCase(val.name), _.lowerCase(value)))
                                ret = true;
                            if (_.includes(_.lowerCase(val.customerNumber), _.lowerCase(value)))
                                ret = true;
                        }

                    })
                    return ret;

                });
            },

            /**
             * fetch all records from DB
             */
            fetchAll() {
                this.loading = true;
                axios.get('/api/search').then((results) => {
                    this.bills = results.data.data;
                    this.dataStore = results.data.data;
                })
                    .finally(() => {
                        this.loading = false;
                    })
            },

            /**
             * opens modal for company editing
             * @param id
             */
            edit(id) {
                this.openAddNewForm = true;
                this.editCompanyId = id;
            },

            /**
             * opens modal for creating new company
             */
            newCompany() {
                this.openAddNewForm = true;
                this.editCompanyId = null;
            },

            /**
             * delete bill from DB and local records
             * @param id
             */
            removeBill(id) {
                this.loading = true;
                axios.delete('/api/bill', {data: {id: id}}).then((response) => {
                    if (response.status === 204) {
                        let billIndex = _.findIndex(this.bills, (n) => {
                            return n.id === id;
                        });
                        if (billIndex !== -1) {
                            this.bills.splice(billIndex, 1);
                        }
                        this.snackBarColor = 'green';
                        this.snackBarText = 'Bill Removed Successfully';
                        this.snackbar = true;
                    }
                })
                    .catch((e) => {
                        this.snackBarColor = 'red';
                        this.snackBarText = e.message;
                        this.snackbar = true;
                        return false;
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },

            /**
             * close the open modal
             * and show success message if new record created
             * @param saved
             */
            handleAddNewForm(saved) {
                this.openAddNewForm = false;
                this.editCompanyId = null;
                if (saved) {
                    this.snackBarColor = 'green';
                    this.snackBarText = 'Bill Saved Successfully';
                    this.snackbar = true;
                }
            }
        }
    }
</script>
