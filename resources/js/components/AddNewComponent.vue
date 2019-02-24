<template>
    <v-dialog v-model="openAdd" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="close(false)">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Manage Company</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn offset-y dark color="primary" @click="reset">Reset Form</v-btn>
                </v-toolbar-items>
            </v-toolbar>

            <v-container fluid>
                <v-layout row wrap>
                    <v-flex xs2>
                        <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                        >
                            <v-combobox
                                    v-model="form.name"
                                    :items="customerNames"
                                    :rules="nameRules"
                                    :loading="namesLoading"
                                    @input="setFilterString"
                                    @input.native="setFilterString"
                                    required
                                    label="Select a name or create a new one"
                            ></v-combobox>

                            <v-text-field
                                    v-model="form.customerNumber"
                                    label="Customer Number"
                                    type="number"
                                    :rules="customerNumberRules"
                                    required
                            ></v-text-field>
                            <v-text-field
                                    v-model="form.cost"
                                    label="Cost"
                                    type="number"
                                    :rules="costRules"
                                    :error="!cumulativeAmount"
                                    prefix="€"
                                    required
                            ></v-text-field>
                            <v-text-field
                                    v-model="form.street"
                                    label="Address"
                                    type="text"
                            ></v-text-field>
                            <v-btn block dark color="primary" @click="addBill">Add Bill</v-btn>
                            <v-btn block dark color="green" @click="validateBeforeSave">Save</v-btn>
                        </v-form>
                    </v-flex>
                    <v-flex xs10 class="pa-5">
                        <v-layout v-if="fetchedBills.length > 0">
                            <v-flex xs12 class="blue--text"><small >** to update old bills press enter key after changing fields **</small></v-flex>
                        </v-layout>
                        <v-layout>
                            <v-flex xs3 class="pa-2">Date</v-flex>
                            <v-flex xs2 class="pa-2">Bill Number</v-flex>
                            <v-flex xs2 class="pa-2">Purpose</v-flex>
                            <v-flex xs2 class="pa-2" :class="cumulativeAmount ? 'green--text' : 'red--text'">Amount<br/>
                                Σ = {{form.cost}} €
                            </v-flex>
                            <v-flex xs2 class="pa-2">Share<br/> Σ = 100%</v-flex>
                            <v-flex xs1 class="pa-2"></v-flex>
                        </v-layout>

                        <v-layout v-for="(item, index) in fetchedBills" :key="index+'f'">
                            <v-flex xs3 class="pa-2">
                                <v-menu
                                        v-model="item.dateShow"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                >
                                    <v-text-field
                                            slot="activator"
                                            v-model="item.dateDisplay"
                                            prepend-icon="mdi-calendar-today"
                                            readonly
                                    ></v-text-field>
                                    <v-date-picker @input="updateBill(index)" v-model="item.date" @change="formatDate(item)"
                                                   ></v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs2 class="pa-2">
                                <v-text-field
                                        v-model="item.billNumber"
                                        type="number"
                                        required
                                        @keyup.enter.native="updateBill(index)"
                                ></v-text-field>

                            </v-flex>
                            <v-flex xs2 class="pa-2">
                                <v-text-field
                                        v-model="item.purpose"
                                        type="text"
                                        required
                                        @keyup.enter.native="updateBill(index)"
                                ></v-text-field>

                            </v-flex>
                            <v-flex xs2 class="pa-2">
                                <v-text-field
                                        v-model="item.amount"
                                        type="number"
                                        required
                                        :error="!cumulativeAmount"
                                        :background-color="(!cumulativeAmount) ? 'red lighten-4' : ''"
                                        prefix="€"
                                        @keyup.enter.native="updateBill(index)"
                                ></v-text-field>

                            </v-flex>
                            <v-flex align-content-center xs2 class="pa-2 text-xs-center">{{item.share}}%</v-flex>
                            <v-flex xs1 class="pa-2">
                                <v-btn color="red" flat icon @click="destroyBill(index)">
                                    <v-icon>mdi-delete-outline</v-icon>
                                </v-btn>

                            </v-flex>
                        </v-layout>
                        <v-layout v-for="(item, index) in form.bills" :key="index">
                            <v-flex xs3 class="pa-2">
                                <v-menu
                                        v-model="item.dateShow"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                >
                                    <v-text-field
                                            slot="activator"
                                            v-model="item.dateDisplay"
                                            prepend-icon="mdi-calendar-today"
                                            readonly
                                            box
                                    ></v-text-field>
                                    <v-date-picker v-model="item.date" @change="formatDate(item)"
                                                   @input="item.dateShow = false"></v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs2 class="pa-2">
                                <v-text-field
                                        v-model="item.billNumber"
                                        type="number"
                                        box
                                        required
                                ></v-text-field>

                            </v-flex>
                            <v-flex xs2 class="pa-2">
                                <v-text-field
                                        v-model="item.purpose"
                                        type="text"
                                        box
                                        required
                                ></v-text-field>

                            </v-flex>
                            <v-flex xs2 class="pa-2">
                                <v-text-field
                                        v-model="item.amount"
                                        type="number"
                                        box
                                        required
                                        :error="!cumulativeAmount"
                                        :background-color="(!cumulativeAmount) ? 'red lighten-4' : ''"
                                        prefix="€"
                                ></v-text-field>

                            </v-flex>
                            <v-flex align-content-center xs2 class="pa-2 text-xs-center">{{item.share}}%</v-flex>
                            <v-flex xs1 class="pa-2">
                                <v-btn color="red" flat icon @click="removeBill(index)">
                                    <v-icon>mdi-delete-outline</v-icon>
                                </v-btn>

                            </v-flex>
                        </v-layout>

                    </v-flex>
                </v-layout>
            </v-container>

        </v-card>

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
        <v-dialog
                v-model="spinner"
                persistent
                width="300"
        >
            <v-card
                    color="primary"
                    dark
            >
                <v-card-text>
                    {{ spinnerText }}
                    <v-progress-linear
                            indeterminate
                            color="white"
                            class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-dialog>
</template>

<script>
    export default {
        name: "AddNewComponent",
        props: ['openAdd', 'id'],
        data: function () {
            return {
                spinnerText: 'Saving...',
                namesLoading: false,
                spinner: false,
                loading: false,
                snackbar: false,
                snackBarText: '',
                snackBarColor: 'red',
                valid: true,
                namesLoaded: false,
                companyId: null,
                customerNames: [],
                fetchedBills: [],
                form: {
                    name: null,
                    customerNumber: null,
                    cost: 0,
                    street: null,
                    bills: []
                },
                nameRules: [
                    v => !!v || 'Name is required',
                ],
                customerNumberRules: [
                    v => !!v || 'Customer Number is required'
                ],
                costRules: [
                    v => !!v || 'Cost is required'
                ],
            }
        },
        watch:{
            // watch for company name
            'form.name': function (newVal, oldVal){
                this.loadNames();
            },
            // watch for props id
            id: function (newVal, oldVal) {
                if (!_.isNil(this.id) && this.openAdd) {
                    this.companyId = this.id;
                }
            },
            // watch for company id change
            companyId: function (newVal, oldVal) {
                if (!_.isNil(newVal)) {
                    this.loadCompany();
                } else {
                    this.fetchedBills = [];
                }
            },
        },
        computed: {
            /**
             * calculate grand total and percentage
             * and return true,false by comparing with actual cost
             * @returns {boolean}
             */
            cumulativeAmount: function () {
                let billsAmount = 0;
                let cost = parseFloat(this.form.cost) || 0;
                for (let bill of this.fetchedBills) {
                    let share = ((parseFloat(bill.amount || 0) / parseFloat(this.form.cost || 0)) * 100).toFixed(2);
                    bill.share = share >= 0 ? share : 0;
                    billsAmount += parseFloat(bill.amount) || 0;
                }
                for (let bill of this.form.bills) {
                    let share = ((parseFloat(bill.amount || 0) / parseFloat(this.form.cost || 0)) * 100).toFixed(2);
                    bill.share = share >= 0 ? share : 0;
                    billsAmount += parseFloat(bill.amount) || 0;
                }
                return cost >= billsAmount;
            }
        },
        methods: {
            // close modal
            close(saved) {
                this.reset();
                this.$emit('close', saved);

            },

            // save new company record
            save() {

                this.spinnerText = "Saving ...";
                this.spinner = true;
                axios.post('/api/company', this.form).then((result) => {
                    if (result.status === 201) {
                        this.close(true);
                    }
                })
                    .catch((e) => {
                        let errText = '';
                        if (e.response.status === 422) {
                            for (let message in e.response.data.errors) {
                                errText += e.response.data.errors[message][0] + " ";
                            }
                        } else {
                            errText = e.message;
                        }

                        this.snackBarColor = 'red';
                        this.snackBarText = errText;
                        this.snackbar = true;
                        return false;
                    })
                    .finally(() => {this.spinner = false;})

            },

            // update company record
            update() {

                this.spinnerText = "Updating ...";
                this.spinner = true;
                this.form.id = this.companyId;
                axios.put('/api/company', this.form).then((result) => {
                    if (result.status === 201) {
                        this.snackBarColor = 'blue';
                        this.snackBarText = 'Updated Successfully';
                        this.snackbar = true;
                        this.form.bills = [];
                        this.loadCompany();
                    }
                })
                    .catch((e) => {
                        let errText = '';
                        if (e.response.status === 422) {
                            for (let message in e.response.data.errors) {
                                errText += e.response.data.errors[message][0] + " ";
                            }
                        } else {
                            errText = e.message;
                        }

                        this.snackBarColor = 'red';
                        this.snackBarText = errText;
                        this.snackbar = true;

                    })
                    .finally(() => {this.spinner = false;})

            },

            // validation before save,update
            validateBeforeSave() {
                if (this.$refs.form.validate() && this.cumulativeAmount) {
                    if (!_.isNil(this.companyId)) {
                        this.update();
                    } else {
                        this.save();
                    }
                } else {
                    this.snackBarColor = 'red';
                    this.snackBarText = 'Please correct the form before saving.';
                    this.snackbar = true;
                    return false;
                }
            },

            // loads the company data for editing
            loadCompany () {
                if (!_.isNil(this.companyId)) {
                    this.spinnerText = "Loading ...";
                    this.spinner = true;
                    axios.get('/api/company/' + this.companyId).then((result) => {
                        let company = result.data.data;
                        if (company) {
                            this.form.name = company.name;
                            this.form.customerNumber = company.customerNumber;
                            this.form.cost = company.cost;
                            this.form.street = company.street;
                            this.fetchedBills = company.invoices;
                        }
                    })
                        .finally(() => {
                            this.spinner = false;
                        })
                }
            },

            // fetch company names
            loadNames () {
                if (!_.isNil(this.form.name) && this.form.name.length > 3 && !this.namesLoading && !this.namesLoaded) {
                    this.namesLoading = true;
                    axios.post('/api/company/search/names', {name: this.form.name}).then((results) => {
                        this.namesLoaded = true;
                        this.customerNames = results.data.data
                    })
                        .finally(() => {this.namesLoading = false;})
                } else {
                    this.namesLoaded = false;
                    this.customerNames = [];
                }
            },

            /**
             * workaround for updating company name model
             * * bug in combobox: it only updates on blur**
             * @param e
             */
            setFilterString (e) {
                if (e && e.target && typeof e.target.value === 'string') {
                    this.form.name = e.target.value;
                } else if (e && typeof e === 'object') {
                    this.form.name = e.text;
                    this.companyId = e.value;
                }
            },

            // add new bill to company
            addBill() {
                this.form.bills.push({
                    dateShow: false,
                    date: null,
                    dateDisplay: null,
                    billNumber: null,
                    purpose: null,
                    amount: 0,
                    share: 0
                });
            },

            // removes a newly created bill
            removeBill(index) {
                this.form.bills.splice(index, 1);
            },

            // delete old bill from DB
            destroyBill(index) {
                this.spinnerText = "Deleting Bill ...";
                this.spinner = true;
                axios.delete('/api/bill', {data: {id: this.fetchedBills[index].id}}).then((response) => {
                    if (response.status === 204) {
                        this.fetchedBills.splice(index, 1);
                        this.snackBarColor = 'green';
                        this.snackBarText = 'Bill Removed Successfully';
                        this.snackbar = true;
                    }
                })
                    .finally(() => {
                        this.spinner = false;
                    });
            },

            // update old company bill
            updateBill (index) {
                let bill = this.fetchedBills[index];
                bill.dateShow = false
                this.spinnerText = "Updating Bill ...";
                this.spinner = true;
                axios.put('/api/bill', bill).then((response) => {
                    if (response.status === 204) {
                        this.snackBarColor = 'green';
                        this.snackBarText = 'Bill Updated Successfully';
                        this.snackbar = true;
                    }
                })
                    .catch((e) => {
                        let errText = '';
                        if (e.response.status === 422) {
                            for (let message in e.response.data.errors) {
                                errText += e.response.data.errors[message][0] + " ";
                            }
                        } else {
                            errText = e.message;
                        }

                        this.snackBarColor = 'red';
                        this.snackBarText = errText;
                        this.snackbar = true;
                        return false;
                    })
                    .finally(() => {
                        this.spinner = false;
                    });
            },

            // format date for bill
            formatDate(bill) {
                if (!bill.date) {
                    bill.dateDisplay = null;
                    return;
                }

                const [year, month, day] = bill.date.split('-')
                bill.dateDisplay = `${month}/${day}/${year}`
            },

            // reset form
            reset() {
                this.$refs.form.reset();
                this.$refs.form.resetValidation();
                this.form = {
                    name: null,
                    customerNumber: null,
                    cost: 0,
                    street: null,
                    bills: []
                };
                this.companyId = null;
                this.fetchedBills = [];


            }
        }
    }
</script>
