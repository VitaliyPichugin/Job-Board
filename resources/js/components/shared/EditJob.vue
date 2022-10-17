<template>
    <div class="root ">
        <teleport to="body">
            <div class="modal-d" v-if="$parent.isOpenEdit">
                <div class="card  card-modal">
                    <h5 class="card-header">Edit Job</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label :for="`exampleFormControlInput1-delete-${job.id}`"  class="form-label">Title</label>
                            <input type="text" class="form-control" v-model="job.title" :id="`exampleFormControlInput1-delete-${job.id}`">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" required v-model="$parent.newTag" placeholder="Tag name (create a tag if no matching tag)" aria-label="Tag name" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" @click="$emit('createTag', $parent.newTag)"  type="button" :id="`button-addon2-delete-${job.id}`">Create</button>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput3" class="form-label">Tags</label>
                            <span class="p-fluid">
                                    <AutoComplete
                                        v-model="job.tags"
                                        :multiple="true"
                                        :suggestions="$parent.filteredItems"
                                        @complete="$parent.searchItems"
                                        :virtualScrollerOptions="{ itemSize: 38 }"
                                        optionLabel="name"
                                    />
                                </span>
                        </div>
                        <div class="mb-3">
                            <label :for="`exampleFormControlTextarea1-delete-${job.id}`" class="form-label">Description</label>
                            <textarea class="form-control" v-model="job.description" :id="`exampleFormControlTextarea1-delete-${job.id}`" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <a href="#" @click="$emit('editJob',job)" class="btn btn-outline-success float-end">Save</a>
                        <a href="#" @click="$parent.isOpenEdit = false" class="btn btn-outline-secondary">Close</a>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>

<script>
export default {
    name: "EditJob",
    props: ['job'],
}
</script>
