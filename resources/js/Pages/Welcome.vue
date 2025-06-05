<template>
  <Head title="Articles" />
  <header role="banner" class="fixed top-0 container flex flex-col justify-center h-12 bg-indigo-900 mb-5">
    <div class="flex">
      <div class="bg-gradient-to-r from-indigo-500 to-indigo-900 h-12 pt-2 px-4">
        <Link href="/article/about">
          <h1 class="text-2xl text-white">Nigel's&nbsp;Scrapbook</h1>
        </Link>
      </div>
      <div class="hidden md:block w-full pt-3 pr-4 text-xs text-amber-500 text-right">
        <span v-html="quip"/>
      </div>
    </div>
  </header>
  <section class="mt-20 container pl-2 flex flex-row justify-items-start flex-wrap">
    <div class="bg-indigo-900 border rounded px-2 py-1 m-2">
      <Link href="/">All</Link>
    </div>
    <div v-for="(title, category) in categories" class="bg-indigo-900 border rounded px-2 py-1 m-2">
      <Link :href="'/?category='+category+'&newest='+newest">{{title}}</Link>
    </div>
    <div class="bg-green-800 border rounded px-2 py-1 m-2"><Link :href="'/?newest=true'+getCurrentCategoryLink()">Newest</Link></div>
    <div class="bg-green-800 border rounded px-2 py-1 m-2"><Link :href="'/?newest=false'+getCurrentCategoryLink()">Oldest</Link></div>
  </section>
  <section class="mt-20 container pl-2 flex flex-row justify-around flex-wrap">
    <div v-for="article in articles" class="bg-slate-800 border border-black hover:border-green-500 rounded-2xl w-[300px] h-64 mx-2 my-2 inline-block">
      <Link :href="'/article/' + article.slug">
        <img :src="'/images/' + article.category +'.jpg'" class="align-middle rounded-t-2xl"/>
        <div class="text-white w-full text-center mt-2 h-2">{{article.title}}</div>
      </Link>
    </div>
  </section>
  <section class="container h-20">
    <div>{{debug}}</div>
    &nbsp;
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
    categories: Array,
    currentCategory: String,
    articles: Array,
    newest: Boolean,
    debug: String,
  },
  data() {
    return {
      'year': 2022,
    };
  },
  mounted() {
    this.year = new Date().getFullYear();
  },
  methods: {
    getCurrentCategoryLink() {
      if (this.currentCategory !== false) {
        return '&category=' + this.currentCategory
      }
      else {
        return ''
      }
    }
  },
}
</script>
