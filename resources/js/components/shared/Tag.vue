<template>
    <div>
        <div class="badge" v-for="tag in tags">
            <span>{{ tag.name }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "Tag",
    props: ['tags']
}
</script>
