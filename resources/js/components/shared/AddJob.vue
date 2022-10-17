<template>
    <div class="row">
        <div class="col">
            <button type="button" @click="$emit('openModal', 'create')" class="btn btn-outline-primary float-end mb-3">
                Add Job
                <i class="bi bi-file-earmark-plus"></i>
            </button>
            <div class="root ">
                <teleport to="body">
                    <div class="modal modal-d" v-if="$parent.isOpen">
                        <div class="card  card-modal">
                            <h5 class="card-header">Add Job</h5>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1"  class="form-label">Title</label>
                                    <input type="text" class="form-control" v-model="$parent.job.title" id="exampleFormControlInput1">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" required v-model="$parent.newTag" placeholder="Tag name (create a tag if no matching tag)" aria-label="Tag name" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-secondary" @click="$emit('createTag', $parent.newTag)"  type="button" id="button-addon2">Create</button>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput3" class="form-label">Tags</label>
                                    <span class="p-fluid">
                                        <AutoComplete
                                            v-model="$parent.job.tags"
                                            :multiple="true"
                                            :suggestions="$parent.filteredItems"
                                            @complete="$parent.searchItems"
                                            :virtualScrollerOptions="{ itemSize: 38 }"
                                            optionLabel="name"
                                        />
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                    <textarea class="form-control" v-model="$parent.job.description" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <a href="#" @click="$emit('createJob', $parent.job)" class="btn btn-outline-primary float-end">Post Job</a>
                                <a href="#" @click="$parent.isOpen = false" class="btn btn-outline-secondary">Close</a>
                            </div>
                        </div>
                    </div>
                </teleport>
            </div>
        </div>
    </div>
</template>
