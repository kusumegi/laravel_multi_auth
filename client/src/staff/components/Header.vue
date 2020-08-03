<template>
    <!-- <v-card color="grey lighten-4" flat tile>
        <v-toolbar dense>
            <v-toolbar-title>
                <span v-show="username != null">{{ username }}さんの</span>
                Todoリスト
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <v-toolbar-items>
                 <v-btn
                    icon
                    to="/register"
                    v-show="username == null"
                    active-class="currPage"
                >
                    <v-icon>person_add</v-icon>
                </v-btn>
                <v-btn
                    icon
                    to="/login"
                    v-show="username == null"
                    active-class="currPage"
                >
                    <v-icon>login</v-icon>
                </v-btn>
                <v-btn icon @click="logout">
                    <v-icon>logout</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
    </v-card> -->
    <div>
        <v-app-bar color="primary" dense dark>
            <!-- <v-toolbar-title>受付</v-toolbar-title> -->

            <v-toolbar-items>
                <v-btn text to="/uketsuke">受 付</v-btn>
                <v-divider vertical></v-divider>
                <v-btn text to="/yoyaku">予 約</v-btn>
                <v-divider vertical></v-divider>
                <v-btn text to="/patient">患者管理</v-btn>
                <v-divider vertical></v-divider>
                <v-menu offset-y>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" text
                            >スタッフ<v-icon>arrow_drop_down</v-icon></v-btn
                        >
                    </template>
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title
                                    >スタッフ検索</v-list-item-title
                                >
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title
                                    >シフト管理</v-list-item-title
                                >
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-menu left bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>more_vert</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="n in 5" :key="n" @click="() => {}">
                        <v-list-item-title>Option {{ n }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
            <v-btn icon>
                <v-icon>logout</v-icon>
            </v-btn>
        </v-app-bar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            drawer: false
        };
    },
    computed: {
        username() {
            const user = this.$store.getters["auth/user"];
            return user != null ? user.name : null;
        }
    },
    methods: {
        /**
         * ログアウト処理
         */
        async logout() {
            try {
                await this.$store.dispatch("auth/logout");
                // 再読み込み
                this.$router.go({
                    path: this.$router.currentRoute.path,
                    force: true
                });
            } catch (error) {
                // 再読み込み
                this.$router.go({
                    path: this.$router.currentRoute.path,
                    force: true
                });
            }
        }
    }
};
</script>
<style scoped>
.currPage {
    display: none;
}
</style>
