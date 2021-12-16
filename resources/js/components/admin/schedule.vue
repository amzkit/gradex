<template>
    <div class="container">
        <v-row class="pt-3"><v-spacer />
            <v-select
                v-model="course_id"
                :items="courses"
                label="Course"
                item-text = "name"
                item-value = "id"
                @change="course_selected"
            >
            </v-select>
        <v-spacer /></v-row>

        <template v-if="schedules.length">
            <v-data-table
                :headers="headers"
                :items="schedules"
                class="elevation-1"
            >
                <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Schedule</v-toolbar-title>
                    <v-divider
                    class="mx-4"
                    inset
                    vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog
                    v-model="dialog"
                    max-width="500px"
                    >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                        color="primary"
                        dark
                        class="mb-2"
                        v-bind="attrs"
                        v-on="on"
                        >
                        New Item
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                        <span class="text-h5">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                        <v-container>
                            <v-select
                                v-model="editedItem.problem_id"
                                :items="problems"
                                label="Problem"
                                item-text = "title"
                                item-value = "id"
                            >
                            </v-select>
                                <v-text-field
                                v-model="editedItem.start_time"
                                label="Start Time"
                                ></v-text-field>

                                <v-text-field
                                v-model="editedItem.end_time"
                                label="End Time"
                                ></v-text-field>

                        </v-container>
                        </v-card-text>

                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="close"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="save"
                        >
                            Save
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                        <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-toolbar>
                </template>
                <template v-slot:item.problem_title="{ item }">
                    <v-edit-dialog
                        :return-value.sync="item.problem_id"
                        large
                        @save="save_cell(item)"
                        @cancel="cancel_cell"
                        @open="open_cell"
                        @close="close_cell"
                    > {{item.problem_title}}
                        <template v-slot:input>
                            <v-select
                                v-model="item.problem_id"
                                :items="problems"
                                label="Problem"
                                item-text = "title"
                                item-value = "id"
                            >
                            </v-select>
                        </template>
                    </v-edit-dialog>
                </template>
                <template v-slot:item.start_time="{ item }">
                    <v-edit-dialog
                        :return-value.sync="item.start_time"
                        large
                        @save="save_cell(item)"
                        @cancel="cancel_cell"
                        @open="open_cell"
                        @close="close_cell"
                    > {{item.start_time}}
                        <template v-slot:input>
                        <v-text-field
                            v-model="item.start_time"
                            label="Edit"
                            single-line
                        ></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template v-slot:item.end_time="{ item }">
                    <v-edit-dialog
                        :return-value.sync="item.end_time"
                        large
                        @save="save_cell(item)"
                        @cancel="cancel_cell"
                        @open="open_cell"
                        @close="close_cell"
                    > {{item.end_time}}
                        <template v-slot:input>
                        <v-text-field
                            v-model="item.end_time"
                            label="Edit"
                            single-line
                        ></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
                </template>
                <template v-slot:no-data>
                <v-btn
                    color="primary"
                    @click="initialize"
                >
                    Reset
                </v-btn>
                </template>
            </v-data-table>
        </template>


    </div>

</template>

<script>
    export default {
        mounted() {
            this.initialize()
        },
        data() {
            return {
                loading: false,

                courses: [],
                course_id: null,

                schedules: [],
                schedule_id: null,

                problems: [],
                problem_id: null,

                dialog: false,
                dialogDelete: false,
                headers: [
                    {
                    text: 'Title',
                    align: 'start',
                    sortable: false,
                    value: 'problem_title',
                    },
                    { text: 'Start Time', value: 'start_time' },
                    { text: 'End Time', value: 'end_time' },
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                editedIndex: -1,
                editedItem: {
                    problem_id: 0,
                    start_time: "",
                    end_time: "",
                },
                defaultItem: {
                    problem_id: 0,
                    start_time: "",
                    end_time: "",
                },
            }
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
            dialogDelete (val) {
                val || this.closeDelete()
            },
        },

        methods:{
            async initialize () {
                await axios.get("/api/course")
                .then(response => {
                    if (response.data.success == true) {
                        this.courses = response.data.courses
                        this.problems = response.data.problems
                    }
                    this.loading = false
                })
                .catch(error => {
                    console.log("getting data error", error);
                    this.loading = false
                });
            },
            async course_selected(){
                console.log("course_id", this.course_id)
                await axios.get("/api/course/schedules", {
                    params:{
                        course_id: this.course_id
                    }
                })
                    .then(response => {
                        if (response.data.success == true) {
                            this.schedules = response.data.schedules
                        }
                        this.loading = false
                    })
                    .catch(error => {
                        console.log("getting data error", error);
                        this.loading = false
                    });
            },
            editItem (item) {
                this.editedIndex = this.schedules.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                this.editedIndex = this.schedules.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogDelete = true
            },

            async deleteItemConfirm () {
                await axios.delete("/api/schedule/destroy", {
                    params:{
                        schedule_id: this.editedItem.id,
                    }
                }).then(response => {
                    if (response.data.success == true) {
                        this.schedules.splice(this.editedIndex, 1)
                    }
                    this.loading = false
                })
                .catch(error => {
                    console.log("getting data error", error);
                    this.loading = false
                });

                this.closeDelete()
            },

            close () {
                this.dialog = false
                this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
                })
            },

            closeDelete () {
                this.dialogDelete = false
                this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
                })
            },

            save () {
                if (this.editedIndex > -1) {
                Object.assign(this.schedules[this.editedIndex], this.editedItem)
                } else {
                this.schedules.push(this.editedItem)
                }
                this.close()
            },
            async save_cell(cell){
                console.log("Save Cell", cell)
                await axios.post("/api/schedule/update", {
                    schedule_id: cell.id,
                    problem_id: cell.problem_id,
                    start_time: cell.start_time,
                    end_time: cell.end_time,
                }).then(response => {
                    if (response.data.success == true) {
                        this.schedule = response.data.schedule
                        cell.problem_title = response.data.schedule.problem_title
                    }
                    this.loading = false
                })
                .catch(error => {
                    console.log("getting data error", error);
                    this.loading = false
                });

            },
            cancel_cell(){
                console.log("Cancel Cell")

            },
            open_cell(){
                console.log("Open Cell")

            },
            close_cell(){
                console.log("Close Cell")

            }
        }
    }
</script>
