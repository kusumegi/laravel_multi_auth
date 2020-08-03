<template>
    <!-- リスト -->

    <v-card>
        <v-toolbar :color="headerColor" dark>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
        </v-toolbar>
        <v-list subheader two-line flat>
            <v-list-item-group>
                <v-list-item
                    v-for="(item, index) in sortedList"
                    :key="index"
                    v-show="showDoneTodo || !item.checked"
                    v-bind:class="classObj(item)"
                >
                    <template>
                        <v-list-item-action>
                            <v-checkbox
                                color="primary"
                                :input-value="item.checked"
                                @change="toggleCheck(item, index)"
                            ></v-checkbox>
                        </v-list-item-action>

                        <v-list-item-content
                            @click="edit(item)"
                            class="text-left"
                        >
                            <v-list-item-title>{{
                                item.subject
                            }}</v-list-item-title>
                            <v-list-item-subtitle
                                ><span v-show="item.limit_at != null">
                                    期限[{{ item.limit_at_day }}
                                    {{ item.limit_at_time }}]
                                </span>
                                <span v-show="item.detail != null">
                                    {{ item.detail }}
                                </span></v-list-item-subtitle
                            >
                        </v-list-item-content>

                        <v-list-item-action v-show="item.checked">
                            <v-btn
                                depressed
                                icon
                                color="red"
                                @click="deleteItem(item)"
                                ><v-icon>delete</v-icon></v-btn
                            >
                        </v-list-item-action>
                    </template>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-card>
</template>

<script>
import moment from "moment";
export default {
    components: {},
    props: {
        title: {
            type: String,
        },
        headerColor: {
            type: String,
        },
        items: {
            type: Array,
        },
        showDoneTodo: {
            type: Boolean,
        },
        sortColumn: {
            type: String,
        },
        sortOrder: {
            type: String,
        },
    },
    // data() {},
    created() {},
    computed: {
        classObj() {
            // 項目の状態から適切な背景色のクラスを返却する
            return (item) => {
                const classObject = {
                    completed: false,
                    pastDate: false,
                    today: false,
                };

                if (item.checked) {
                    classObject.completed = true;
                } else if (item.isPastDate) {
                    classObject.pastDate = true;
                } else if (item.isToday) {
                    classObject.today = true;
                }
                return classObject;
            };
        },
        /**
         * ソート対象カラムでソートしたリスト
         */
        sortedList() {
            const list = this.items;
            list.sort((a, b) => {
                // ソート対象日付を取得
                const dateA = moment(a[this.sortColumn]);
                const dateB = moment(b[this.sortColumn]);

                // 同値
                if (dateA.isSame(dateB)) {
                    return 0;
                }

                // 昇順または降順でソート
                const order = this.sortOrder == "asc" ? 1 : -1;
                return dateA.isBefore(dateB) ? -1 * order : 1 * order;
            });
            return list;
        },
    },
    watch: {},
    methods: {
        /**
         * リストを表示する
         */
        dispList(dispItems) {
            this.items = dispItems;
        },

        /**
         * 編集対象として選択
         */
        edit(currItem) {
            // 親コンポーネントに伝達
            this.$emit("edit", currItem);
        },

        /**
         * 削除
         */
        deleteItem(currItem) {
            // 親コンポーネントに伝達
            this.$emit("deleteItem", currItem);
        },

        /**
         * チェックボックスの切り替え
         */
        toggleCheck(item, index) {
            item.checked = !item.checked;
            this.items.splice(index, 1, item);

            this.$emit("update", item);
        },
    },
};
</script>

<style>
.completed {
    background-color: #eeeeee;
}
.pastDate {
    background-color: #fce4ec;
}
.today {
    background-color: #fff3e0;
}
</style>
