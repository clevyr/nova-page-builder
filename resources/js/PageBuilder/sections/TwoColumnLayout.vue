<template>
    <div class="flex p-4">
        <div class="w-1/2 p-4 border-r-2">
            <div v-html="content.left_col"></div>
        </div>
        <div class="w-1/2 p-4">
            <div v-html="content.right_col"></div>
        </div>
    </div>
</template>

<script>
    export default {
    	props: ['content']
    }
</script>
