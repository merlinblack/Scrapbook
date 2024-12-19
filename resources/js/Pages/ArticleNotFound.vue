<template>
  <Head title="Article not found" />
  <header role="banner" class="fixed top-0 container mx-auto flex flex-col justify-center h-12 bg-indigo-900 mb-5">
    <div class="flex">
      <div class="bg-gradient-to-r from-indigo-500 to-indigo-900 h-12 pt-2 px-4">
        <Link href="/">
          <h1 class="text-2xl text-white hover:underline">Nigel's&nbsp;Scrapbook</h1>
        </Link>
      </div>
      <div class="hidden md:block bg-indigo-900 h-12 pt-4 px-4 w-2/3">
      </div>
    </div>
  </header>
  <section class="container px-16 mt-20 mb-16">
    <h1 class="text-xl">Article not found</h1>
    <a href="/"><p class="text-base text-yellow-500 underline mt-7">Go back to main page.</p></a>
  </section>
  <footer role="none" class="fixed bottom-0 h-16 container mx-auto flex flex-col bg-gradient-to-b from-black to-slate-800">
    <div class="pt-8 text-center">Copyright © Nigel Atkinson 2011-{{year}} | <Link href="/article/about" class="underline">About</Link> | <Link href="/" class="underline">Back to index</Link></div>
  </footer>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
  components: {
    Head,
    Link,
  },
  data() {
    return {
      'year': 2025,
    };
  },
  mounted() {
    this.year = new Date().getFullYear();
  }
}
</script>

