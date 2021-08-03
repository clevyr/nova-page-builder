<template>
    <div class="flex p-4">
        <div class="w-full">
            <div v-html="content.content"></div>
        </div>
    </div>
</template>

<script>
    export default {
    	props: ['content']
    }
</script>
