<template>
    <div class="rbt-data-table">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center w-100">Income</h4>
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
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline4" class="custom-control-input" v-model="type" value="date">
                                <label class="custom-control-label" for="customRadioInline4">Date</label>
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
                            <div class="data-search float-sm-left">
                                <div class="data-search" v-if="type === 'date'">
                                    <label for="date_id">
                                        Pick Date:&nbsp;
                                        <div id="date_id">
                                            <div class="form-group" id="date_id_start" style="width: auto !important;" v-model="dateId">
                                                <label class="">Start Date : </label>
                                                <input type="date" id="start" name="start" class="form-control">
                                            </div>
                                            <div class="form-group" id="date_id_end" style="width: auto !important;" v-model="dateId">
                                                <label class="">End Date : </label>
                                                <input type="date" id="end" name="end" class="form-control">
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-block btn-primary"><span style="font-size: 15px;">Search</span></button>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="FranchiseTable1" style="width: 100%;">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Method</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Received By</th>
                            <th scope="col" v-if="type === 'all'">Purpose</th>
                            <th scope="col" v-if="type === 'all'">Project</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr v-for="data in paginatedData.data">

                            <th scope="row">{{ data.date }}</th>
                            <td>{{ data.method }}</td>
                            <td class="font-weight-bold">{{ data.amount + '.00' }}</td>
                            <td>{{ data.by }}</td>
                            <td v-if="type === 'all'" style="text-transform: capitalize;">{{ data.purpose }}</td>
                            <td v-if="type === 'all'">
                                <a :href="'/project/show/' + data.project_id">{{ data.project_name }}</a>
                            </td>
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
                                    <li class="page-item pagination-page-nav" v-if="paginatedData.data && paginatedData.data.length === parseInt(perPage)">
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
                apiUrl: '/bsoft-api/income/',
                perPage: 15,
                search: '',
                type: 'all',
                projectId: '',
            }
        },
        created() {
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

<!--<script>-->
<!--    $('#FranchiseTable1').DataTable( {-->
<!--        responsive: true,-->
<!--        dom: 'Bfrtip',-->
<!--        buttons: [-->
<!--            'csv', 'pdf', 'print'-->
<!--        ]-->
<!--    } );-->
<!--    $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');-->
<!--</script>-->
