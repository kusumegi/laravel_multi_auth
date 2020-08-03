<template>
    <div id="app">
        <v-app>
            <!-- ヘッダー -->
            <Header></Header>
            <!-- コンテンツ表示部 -->
            <router-view />
        </v-app>
    </div>
</template>

<script>
import Header from "./components/Header";
export default {
    name: "App",

    components: {
        Header
    },

    data: () => ({
        //
    })
};
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
}

#nav {
    padding: 30px;
}

#nav a {
    font-weight: bold;
    color: #2c3e50;
}

#nav a.router-link-exact-active {
    color: #42b983;
}
</style>
