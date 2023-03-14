<template>
    <Head :title="article.title" />
    <header role="banner" class="fixed top-0 container mx-auto flex flex-col justify-center h-12 bg-indigo-900 mb-5">
        <div class="flex">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-900 h-12 pt-2 px-4">
                <Link href="/">
                    <h1 class="text-2xl text-white">Nigel's&nbsp;Scrapbook</h1>
                </Link>
            </div>
            <div class="hidden md:block bg-indigo-900 h-12 pt-4 px-4 w-2/3">
                {{article.title}}
            </div>
            <div class="hidden md:block w-full pt-3 pr-4 text-xs text-amber-500 text-right">
                <span v-html="quip"/>
            </div>
        </div>
    </header>
    <section class="container px-16 mt-20 mb-16">
        <div class="md-article" v-html="article.html"/>
    </section>
    <footer role="footer" class="fixed bottom-0 h-16 container mx-auto flex flex-col bg-gradient-to-b from-black to-slate-800">
        <div class="pt-8 text-center">Copyright © Nigel Atkinson 2011-{{year}} | <Link href="/article/about" class="underline">About</Link></div>
    </footer>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Head,
        Link,
    },
    props: {
        quip: String,
        article: Object
    },
    data() {
        return {
            'year': 2022,
        };
    },
    mounted() {
        this.year = new Date().getFullYear();
    }
}
</script>
