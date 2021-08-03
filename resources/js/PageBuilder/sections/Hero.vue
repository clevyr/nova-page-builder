<template>
    <div class="hero py-12 bg-local bg-cover bg-no-repeat"
         :style="'background-image: url(/storage/'+ content.image + ')'"
    >
        <div class="container mx-auto">
            <h1 class="text-4xl text-white bg-black p-5 inline">{{ content.heading }}</h1>
        </div>
    </div>
</template>

<script>
    export default {
    	props: ['content'],
    }
</script>
