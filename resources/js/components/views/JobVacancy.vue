<template>
    <div class="container ">
        <AddJob @refresh="getData"/>
        <div class="row" v-for="job in list">
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
                        <div class="mt-3"> <span class="text1">Proposals: <span class="text2">{{ job.responses.length }}</span></span> </div>
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
                                <button @click="sendResponse(job.id, job.user_id)" class="btn btn-success m-1 float-end">
                                    <i class="bi bi-send" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-success m-1 float-end">
                                    <i class="bi bi-trash " aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-success m-1 float-end">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </button>
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
import Tags from "../shared/Tag.vue"

export default {
    components: {AddJob, Tags},
    data: () => ({
        list:{},
        likes: {},
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
        like(id, type) {
            axios.put(`/job/like`, {id: id, type: type}).then((r) => {
                this.getData()
            });
        },
        sendResponse(job_id, author_id) {
            axios.post('/job/send', {job_id: job_id, author_id: author_id})
                .then((res) => {
                    switch (res.status) {
                        case  200:
                            this.$toast.success(res.data, {position: 'top-right'});
                            break;
                        case  201:
                            this.$toast.info(res.data, {position: 'top-right'});
                            break;
                    }
                    this.getData()
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message, {position: 'top-right'});
                })
        },
    },
    mounted() {
        this.getData()
    }
}
</script>
