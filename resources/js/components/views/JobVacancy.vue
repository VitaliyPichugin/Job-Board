<template>
    <div class="container ">
        <AddJob @openModal="openModal"
                @createJob="createJob"
                @createTag="createTag"
        />
        <EditJob @openModal="openModal"
                 :job="dataJobEdit"
                 @createTag="createTag"
                 @editJob="editJob"
        />
        <DelJob @openModal="openModal"
                :job="dataJobDelete"
                @deleteJob="deleteJob"
        />
        <div class="row" v-for="(job, index) in list" :key="index">
            <div class="col">
                <div class="card p-3 mb-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <div class="icon"> <i class="bi bi-person-square"></i> </div>
                            <div class="ms-2 c-details">
                                <h6 class="mb-0">{{ job.user.name }}</h6> <span>{{ job.diff_human }}</span>
                            </div>
                        </div>
                        <Tags :tags="job.tags"/>
                    </div>
                    <div class="mt-3">
                        <h3 class="heading ">{{ job.title }}</h3>
                        <p class="text ">{{ job.description }}</p>
                        <div class="mt-3">
                            <span class="text1">Proposals: <span class="text2">{{ job.responses.length }}</span></span>
                        </div>
                        <div class="row align-content-center mt-2">
                            <div class=" col-10">
                                <div class="btn-group-sm float-start">
                                    <span @click="like(job.id, 'job')">
                                        <i  class="bi ico" :class="job.class_job"></i>
                                    </span>
                                    <span @click="like(job.user_id, 'user')">
                                        <i class="bi ico" :class="job.class_user"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2">
                                <button @click="sendResponse(job.id, job.user_id)" class="btn btn-outline-success m-1 float-end">
                                    <i class="bi bi-send" aria-hidden="true"></i>
                                </button>
                                <div v-if="job.user_id == userId ">
                                    <button @click="openModal('delete', job)" class="btn btn-outline-danger m-1 float-end">
                                        <i class="bi bi-trash " aria-hidden="true"></i>
                                    </button>
                                    <button @click="openModal('update', job)" class="btn btn-outline-secondary m-1 float-end">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddJob from "../shared/AddJob.vue"
import EditJob from "../shared/EditJob.vue"
import DelJob from "../shared/DelJob.vue"
import Tags from "../shared/Tag.vue"
export default {
    components: {AddJob, EditJob, DelJob, Tags},
    data: () => ({
        list: {},
        likes: {},
        userId: 0,
        job: {
            title: '',
            description: '',
            tags: null,
        },
        filteredItems: null,
        items: null,
        newTag: null,
        isOpen: false,
        isOpenEdit: false,
        isOpenDelete: false,
        dataJobEdit: {},
        dataJobDelete: {},
    }),
    methods: {
        getData() {
            axios.get('/job/all')
                .then((response) => {
                        this.likes = response.data.likes;
                        this.list = response.data.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
                            .map((item) => {
                                item.class_job = response.data.likes.like_jobs.includes(item.id) ? 'bi-star-fill' : 'bi-star';
                                item.class_user = response.data.likes.like_users.includes(item.user.id) ? 'bi-person-plus-fill' : 'bi-person-plus';
                                return item;
                            })
                    }
                );
        },
        searchItems(event) {
            let query = event.query;
            let filteredItems = [];
            for (let i = 0; i < this.items.length; i++) {
                let item = this.items[i];
                if (item.name.toLowerCase().indexOf(query.toLowerCase()) === 0) {
                    filteredItems.push(item);
                }
            }
            this.filteredItems = filteredItems;
        },
        createTag(newTag) {
            axios.post(`/tag/store`, {name: newTag})
                .then(() => {
                    this.$toast.success(`Tag "${newTag}" created!`, {position: 'top-right'});
                    this.getTags();
                })
                .catch((error) => {
                    this.$toast.error('Tag already exist or empty field', {position: 'top-right'});
                })
        },
        like(id, type) {
            axios.put(`/job/like`, {id: id, type: type}).then((r) => {
                this.getData()
            });
        },
        createJob(job) {
            axios.post(`/job/store`, job)
                .then((res) => {
                    this.sendToast(res);
                    this.getData();
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message, {position: 'top-right'});
                })
            this.isOpen = false;
        },
        editJob(job) {
            axios.post(`/job/update/${job.id}`, job)
                .then((res) => {
                    this.sendToast(res);
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message, {position: 'top-right'});
                })
            this.isOpenEdit = false;
        },
        deleteJob(id) {
            axios.delete(`/job/${id}`)
                .then((res) => {
                    this.sendToast(res);
                    this.getData();
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message, {position: 'top-right'});
                })
            this.isOpenDelete = false;
        },
        sendResponse(job_id, author_id) {
            axios.post('/job/send', {job_id: job_id, author_id: author_id})
                .then((res) => {
                    this.sendToast(res);
                    this.getData()
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message, {position: 'top-right'});
                })
        },
        getTags(){
            axios.get('/tag/all').then((response) => this.items = response.data)
        },
        sendToast(res) {
            switch (res.status) {
                case  200:
                    this.$toast.success(res.data, {position: 'top-right'});
                    break;
                case  201:
                    this.$toast.info(res.data, {position: 'top-right'});
                    break;
                case  409:
                    this.$toast.info('Tag already exist', {position: 'top-right'});
                    break;
            }
        },
        openModal(type, data) {
            switch (type){
                case 'create':
                    this.isOpen = true;
                    break;
                case 'delete':
                    this.isOpenDelete = true;
                    this.dataJobDelete = data;
                    break;
                case 'update':
                    this.isOpenEdit = true;
                    this.dataJobEdit = data;
                    break;
            }
        },
    },
    mounted() {
        this.getData();
        this.getTags();
        this.userId = window.$userId;
    }
}
</script>
