<template>
    <div class="root">
        <teleport to="body">
            <div class="modal-d" v-if="$parent.isOpenDelete">
                <div class="card card-modal">
                    <h3 class="card-header">Delete: <strong>{{job.title}}</strong>?</h3>
                    <div class="card-footer ">
                        <a href="#" @click="$emit('deleteJob',job.id)" class="btn btn-outline-danger float-end">Delete</a>
                        <a href="#" @click="$parent.isOpenDelete = false" class="btn btn-outline-secondary">Close</a>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>

<script>
export default {
    name: "DelJob",
    props: ['job'],
}
</script>


