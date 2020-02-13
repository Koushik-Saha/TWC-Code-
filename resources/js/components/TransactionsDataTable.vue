<template>
    <div class="rbt-data-table">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center w-100">Cash Ledger</h4>
            </div>
            <div class="card-body">

                <div class="selection-form">
                    <form>
                        <div class="form-group">
                            <strong class="font-weight-bold">Select Type: &nbsp;&nbsp;</strong>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" class="custom-control-input" v-model="type" value="all">
                                <label class="custom-control-label" for="customRadioInline1">All</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" class="custom-control-input" v-model="type" value="loan">
                                <label class="custom-control-label" for="customRadioInline2">Loans</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline3" class="custom-control-input" v-model="type" value="project">
                                <label class="custom-control-label" for="customRadioInline3">By Project</label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="data-table-header" v-if="!isLoading">
                    <div class="row justify-content-between">
                        <div class="col-sm-4 d-none d-sm-block">
                            <div class="data-per-page">
                                <label>
                                    Show:
                                    <select v-model="perPage" class="custom-select">
                                        <option value="15">15</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="data-search float-right">
                                <!--<label class="sr-only" for="Search">Search</label>
                                <div class="input-group">
                                    <input v-model="search" @keyup="fetchData()" type="text" class="form-control" id="Search" placeholder="Search Here">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="feather icon-search text-dark"></i></div>
                                    </div>
                                </div>-->
                                <div class="data-search" v-if="type === 'project'">
                                    <label for="project_id">
                                        For Project:&nbsp;
                                        <select class="custom-select" id="project_id" style="width: auto !important;" v-model="projectId">
                                            <option value="">Select A Project</option>
                                            <option v-for="project in projects" :value="project.project_id">{{ project.project_name }}</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="FranchiseTable" style="width: 100%;">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Method</th>
                            <th scope="col">Type</th>
                            <th scope="col">Amounts</th>
                            <th scope="col">Purpose</th>
                            <th scope="col" v-if="type === 'all'">Project</th>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Received By</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="data in paginatedData.data">

                            <th scope="row">{{ data.date }}</th>
                            <td>{{ data.method }}</td>
                            <td>{{ data.type }}</td>
                            <td class="font-weight-bold">{{ data.amount + '.00' }}</td>
                            <td style="text-transform: capitalize;">{{ data.purpose }}</td>
                            <td v-if="type === 'all'">
                                <a :href="'/project/show/' + data.project_id">{{ data.project_name }}</a>
                            </td>
                            <td>{{ data.from }}</td>
                            <td>{{ data.to }}</td>
                            <td>{{ data.by }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="data-table-footer">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="data-showing">
                                Showing <strong>{{ paginatedData.from }} - {{ paginatedData.to }}</strong>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="data-pagination">
                                <ul class="pagination float-right">
                                    <li class="page-item pagination-page-nav" v-if="paginatedData.current_page > 1">
                                        <a href="#" class="page-link" @click.prevent="previousPage">
                                            <i class="fa fa-angle-double-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item pagination-page-nav" v-if="paginatedData.current_page > 1">
                                        <a href="#" class="page-link" @click.prevent="fetchData(1)">
                                            1
                                        </a>
                                    </li>
                                    <li class="page-item pagination-page-nav active" v-if="paginatedData.current_page">
                                        <a href="#" class="page-link">
                                            {{ paginatedData.current_page }}
                                        </a>
                                    </li>
                                    <li class="page-item pagination-page-nav" v-if="paginatedData.current_page !== paginatedData.last_page">
                                        <a href="#" class="page-link" @click.prevent="nextPage">
                                            <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['projects'],
        data() {
            return {
                isLoading: true,
                paginatedData: {},
                apiUrl: '/bsoft-api/cash-transactions/',
                perPage: 15,
                search: '',
                type: 'all',
                projectId: '',
                deleteFranchiseId: ''
            }
        },
        mounted() {
            this.fetchData();
        },
        watch: {
            perPage(newVal, oldVal) {
                if(newVal !== oldVal)
                    this.fetchData();
            },
            type(newVal, oldVal) {
                if(newVal !== oldVal) {
                    if(newVal === 'project') {
                        this.paginatedData = {};
                    }
                    else {
                        this.fetchData();
                    }
                }
            },
            projectId(newVal, oldVal) {
                if(newVal !== oldVal)
                    this.fetchData();
            }
        },
        methods: {
            fetchData(page = 1) {
                let self = this;
                self.isLoading = true;
                let url = self.apiUrl + self.type;
                if(self.type === 'project') {
                    url = url + '/' + self.projectId;
                }
                axios.get(`${url}?page=${page}&per_page=${self.perPage}&search=${self.search}`)
                    .then(function (response) {
                        console.log(response.data);
                        self.paginatedData = response.data;
                        self.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error.response);
                    });
            },
            previousPage() {
                let page = this.paginatedData.current_page - 1;
                this.fetchData(page);
            },
            nextPage() {
                let page = this.paginatedData.current_page + 1;
                this.fetchData(page);
            },
            openDeleteModal(id) {
                this.deleteFranchiseId = id;
                $('#franchiseDeleteModal').modal('show');
            },
            deleteFranchise() {
                axios.delete('/bs-admin-api/franchise-control/delete/' + this.deleteFranchiseId)
                    .then((response) => {
                        this.showToastMsg('Franchise deleted successfully...!', 'success', 3000);
                        $('#franchiseDeleteModal').modal('hide');
                        this.fetchData();
                    })
                    .catch((error) => {
                        this.showToastMsg('Something went wrong...! Try again later.', 'error', 5000);
                    });
            },
            showToastMsg(msg, method = 'show', duration = 2500) {
                this.$toasted[method](msg, {
                    action : {
                        text : '',
                        icon: 'times',
                        onClick : (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    },
                    duration: duration
                });
            },
        }
    }
</script>
