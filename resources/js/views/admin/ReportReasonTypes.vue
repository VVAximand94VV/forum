<template>
    <div class="row mb-3">
        <div class="col-md-8">
            <!-- Table card -->
            <div class="card rounded-0" style="border-top: 5px solid #0c63e4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>Report reason types list</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-1">
                        <div v-if="reportReasons!==0"
                            class="d-flex mt-2 flex-column flex-md-row flex-lg-row flex-xl-row justify-content-center justify-content-md-between justify-content-lg-between mb-3">
                            <!-- Change showing count entries -->
                            <div class="d-none d-md-flex d-lg-flex d-xl-flex my-2">
                                <span class="form-text">
                                  Show
                                </span>
                                <select v-model="entriesOnPage" class="form-select form-select-sm mx-2" aria-label="Select entries">
                                    <option value="10" selected>10</option>
                                    <option value="20">30</option>
                                    <option value="30">50</option>
                                </select>
                                <span class="form-text">entries</span>
                            </div>
                            <!-- ./ -->
                            <div class="d-flex mx-2 my-2">
                                <label class="form-text mx-1">Search: </label>
                                <input type="search" class="form-control" id="search" style="max-height: 20px;"/>
                            </div>
                        </div>

                        <!-- Table -->
                        <table v-if="reportReasons!==0" class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                            <tr></tr>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Author</th>
                                <th v-if="checkHasPermissions([AccessPermissions.CAN_CREATE_REPORT_REASON_TYPE])" scope="col">Status</th>
                                <th scope="col">Created date</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="reportType in reportReasons">
                                    <th scope="row">{{ reportType.id }}</th>
                                    <td contenteditable @focusout.prevent="update($event, reportType.id, reportType.name)">{{ reportType.name }}</td>
                                    <td>{{ reportType.author }}</td>
                                    <td  v-if="checkHasPermissions([AccessPermissions.CAN_UPDATE_REPORT_REASON_TYPE])">
                                        <div class="btn-group  btn-group-sm" role="group"
                                             aria-label="Basic radio toggle button group"
                                             @change.prevent="changeVisibility($event, reportType.id)">
                                            <input type="radio" class="btn-check" :name="reportType.id+'isPublished'"
                                                   :id="reportType.id+'hide'" value="false" autocomplete="off"
                                                   :checked="reportType.status != true">
                                            <label class="btn btn-outline-secondary" :for="reportType.id+'hide'">Hide</label>

                                            <input type="radio" class="btn-check" :name="reportType.id+'isPublished'"
                                                   :id="reportType.id+'published'" value="true" autocomplete="off"
                                                   :checked="reportType.status == true">
                                            <label class="btn btn-outline-success" :for="reportType.id+'published'">Publish</label>
                                        </div>
                                    </td>
                                    <td>{{ reportType.created_at }}</td>
                                    <td>
                                        <button  v-if="checkHasPermissions([AccessPermissions.CAN_DELETE_REPORT_REASON_TYPE])" @click.prevent="deleteReportReason(reportType.id)" class="btn btn-danger mx-2" title="Edit">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="reportReasons===0" class="text-center mx-1">
                            <h4>You haven't report reason types.</h4>
                        </div>
                    </div>

                    <TablePagination
                        v-if="paginate.last_page > 1"
                        @selectPageEmit="selectPage"
                        :total-entries="paginate.total"
                        :total-pages="paginate.last_page"
                        :links="paginate.links"
                        :current-page="paginate.current_page"
                        :last-page="paginate.last_page" />
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <CreateReportReasonTypeComponent v-if="checkHasPermissions([AccessPermissions.CAN_CREATE_REPORT_REASON_TYPE])" />
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import TablePagination from "../../components/admin/TablePagination.vue";
import CreateReportReasonTypeComponent from "../../components/admin/CreateReportReasonTypeComponent.vue";
import {checkHasPermissions} from "../../access/service";
import AccessPermissions from "../../access/permissions";
export default {
    name: "ReportReasonTypes",
    components: {CreateReportReasonTypeComponent, TablePagination},

    computed: {
        ...mapGetters({
            reportReasons: 'reportReason/getReportReasonType',
            paginate: 'reportReason/getPaginate',
        }),
    },

    setup() {
        return {
            checkHasPermissions,
            AccessPermissions,
        }
    },

    mounted() {
        this.$store.dispatch('reportReason/getReportReasonType');
    },

    data() {
        return {
            entriesOnPage: 10,
        }
    },

    watch: {
        entriesOnPage(val){
            this.$store.commit('reportReason/setEntriesOnPage', val);
            this.$store.dispatch('reportReason/getReportReasonType');
        }
    },

    methods: {
        selectPage(page){
            this.$store.dispatch('reportReason/getReportReasonType', page)
        },

        update(event, id, reasonName){
            let name = event.target.innerText;
            if (name === reasonName) return;
            if (name.length < 6) {
                this.t$.error("Min length 6.");
                return;
            }
            this.$store.dispatch('reportReason/update', [id, name]);
        },

        changeVisibility(event, id){
            this.$store.dispatch('reportReason/changeStatus', id)
        },

        deleteReportReason(id){
            this.$store.dispatch('reportReason/delete', id);
        }
    },
}
</script>

<style scoped>

</style>
