<template>
    <div class="row">
        <div class="col">
            <button type="button" @click="openModal" class="btn btn-outline-primary float-end mb-3">
                Add Job
                <i class="bi bi-file-earmark-plus"></i>
            </button>
            <div class="root ">
                <teleport to="body">
                    <div class="modal" v-if="isOpen">
                        <div class="card  ">
                            <h5 class="card-header">Add Job</h5>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1"  class="form-label">Title</label>
                                    <input type="text" class="form-control" v-model="job.title" id="exampleFormControlInput1">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" required v-model="newTag" placeholder="Tag name (create a tag if no matching tag)" aria-label="Tag name" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-secondary" @click="createTag()"  type="button" id="button-addon2">Create</button>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput3" class="form-label">Tags</label>
                                    <span class="p-fluid">
                                        <AutoComplete
                                            v-model="job.tags"
                                            :multiple="true"
                                            :suggestions="filteredItems"
                                            @complete="searchItems"
                                            :virtualScrollerOptions="{ itemSize: 38 }"
                                            optionLabel="name"
                                        />
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                    <textarea class="form-control" v-model="job.description" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <a href="#" @click="createJob" class="btn btn-primary float-end">Post Job</a>
                                <a href="#" @click="isOpen = false" class="btn btn-primary">Close</a>
                            </div>
                        </div>
                    </div>
                </teleport>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AddJob",
    data: () => ({
        job: {
            title: '',
            description: '',
            tags: null,
        },
        isOpen: false,
        filteredItems: null,
        newTag: null,
    }),
    methods: {
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
        createTag() {
            axios.post(`/tag/store`, {name: this.newTag})
                .then(() => {
                    this.$toast.success(`Tag "${this.newTag}" created!`, {position: 'top-right'});
                    this.getTags();
                })
                .catch((error) => {
                    this.$toast.error('Tag already exist or empty field', {position: 'top-right'});
                })
        },
        createJob() {
            axios.post(`/job/store`, this.job)
                .then((res) => {
                    this.sendToast(res);
                    this.$emit('refresh');
                })
                .catch((error) => {
                    this.$toast.error(error.response.data.message, {position: 'top-right'});
                })
            this.isOpen = false;
        },
        getTags() {
            axios.get('/tag/all').then((response) => this.items = response.data)
        },
        openModal() {
            this.getTags();
            this.isOpen = true;
        },
        sendToast(res){
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
        }
    },
}
</script>

<style scoped>
.root{
    position: relative;
}
.modal{
    z-index: 1;
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgb(0,0,0,0.1);
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.card{
    width: 30%;
}
</style>
