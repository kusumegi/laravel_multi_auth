<template>
    <v-container class="mx-auto">
        <v-row justify="center">
            <v-col col="12">
                <v-form ref="form" v-model="valid" :lazy-validation="lazy">
                    <simple-alert-Message
                        :message="alertMsg"
                        :type="alertType"
                    ></simple-alert-Message>
                    <v-card>
                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="item.subject"
                                        label="やりたいこと"
                                        :rules="subjectRules"
                                        required
                                        @keyup.enter.ctrl="newEnter"
                                        autocomplete="no"
                                    >
                                    </v-text-field>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="6" sm="6" md="4">
                                    <date-picker
                                        ref="datePicker"
                                        :targetDate="item.limit_at_day"
                                        @selectDate="selectDate"
                                    >
                                    </date-picker>
                                </v-col>
                                <v-col cols="6" sm="6" md="4">
                                    <time-picker
                                        ref="timePicker"
                                        :targetTime="item.limit_at_time"
                                        @selectTime="selectTime"
                                    >
                                    </time-picker>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-textarea
                                        label="詳細"
                                        outline
                                        v-model="item.detail"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                            <v-row justify="center">
                                <v-col cols="6" sm="4" md="4" offset-sm2>
                                    <v-btn
                                        class="btn"
                                        block
                                        color="secondary"
                                        @click="clear"
                                        >クリア</v-btn
                                    ></v-col
                                >
                                <v-col
                                    cols="6"
                                    sm="4"
                                    md="4"
                                    v-show="!isUpdate"
                                >
                                    <v-btn
                                        class="btn"
                                        block
                                        color="primary"
                                        :disabled="!valid"
                                        @click="create"
                                        >追加</v-btn
                                    >
                                </v-col>
                                <v-col cols="6" sm="4" md="4" v-show="isUpdate">
                                    <v-btn
                                        class="btn"
                                        block
                                        color="success"
                                        :disabled="!valid"
                                        @click="update(item)"
                                        >更新</v-btn
                                    >
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card>
                </v-form>
            </v-col>
        </v-row>
        <!-- リストコントロール部 -->
        <v-row justify="end" align="center">
            <!-- <v-col cols="6" sm="4" md="3" lg="2"> -->
            <v-col col="5" sm="5" md="3">
                <v-switch
                    v-model="showDoneTodo"
                    label="完了した項目を表示"
                    class="mx-1"
                ></v-switch>
            </v-col>
        </v-row>
        <!-- リスト -->
        <v-row justify-start>
            <v-col
                col="12"
                sm="6"
                v-for="(group, index) in itemGroups"
                :key="index"
            >
                <todo-list-group
                    ref="listGroup"
                    :title="group.title"
                    :headerColor="group.headerColor"
                    :items="group.lists"
                    :sortColumn="group.sortColumn"
                    :sortOrder="group.order"
                    :showDoneTodo="showDoneTodo"
                    @edit="edit"
                    @update="update"
                    @deleteItem="deleteItem"
                ></todo-list-group>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";
import moment from "moment";
import TodoListGroup from "../components/TodoListGroup";
import DatePicker from "../components/DatePicker";
import TimePicker from "../components/TimePicker";
import SimpleAlertMessage from "../components/SimpleAlertMessage";

export default {
    components: {
        TodoListGroup,
        DatePicker,
        TimePicker,
        SimpleAlertMessage
    },
    metaInfo: {
        title: "TODOリスト",
        htmlAttrs: {
            lang: "ja"
        }
    },
    data() {
        return {
            alertType: "",
            alertMsg: null,
            valid: true,
            userName: "",
            isUpdate: false,
            showDoneTodo: false,
            item: {},
            allItems: [],
            itemGroups: [],
            subjectRules: [v => !!v || "やりたいことを入力してください"],
            serverError: null
        };
    },
    watch: {},
    created() {
        alert("Todo.vue:created!");
        // 画面表示前、ログイン済みでなければログイン画面へ
        // this.getUser();
        // alert("Todo.vue:getUser OK!");
        const user = this.$store.getters["auth/user"];
        if (user == null) {
            alert("Todo.vue:user none!");
            this.$router.push("/login");
            return;
        }

        // 未認証であれば仮登録画面へ
        if (user.email_verified_at == null) {
            alert("Todo.vue:email_verified_at none!");
            this.$router.push("/tmpLogin");
            return;
        }

        // 画面表示時にリストを取得
        alert("Todo.vue:user OK!");
        this.userName = this.$store.getters["auth/user"].name;
        this.getList();
    },
    computed: {
        /**
         * ユーザ情報を取得する
         */
        // user() {
        //     alert(`Todo.vue:${this.$store.getters["auth/user"]}`);
        //     return this.$store.getters["auth/user"];
        // },
    },
    methods: {
        /**
         * ユーザー最新取得
         */
        // async getUser() {
        //     await this.$store.dispatch("auth/getLoginUser");
        // },

        /**
         * リスト取得(select)
         */
        async getList() {
            try {
                const res = await axios.get("/api/todo");
                this.allItems = res.data;

                // 表示用データを保持する
                for (const item of this.allItems) {
                    this.createDispInfo(item);
                }

                // グループごとの項目リストを作成する
                this.createItemList();
            } catch (error) {
                this.errorProc(error);
            }
        },

        /**
         * 新規追加(insert)
         */
        async create() {
            try {
                // 締切日時を設定する
                this.item.limit_at = this.getDate(
                    this.item.limit_at_day,
                    this.item.limit_at_time
                );

                // 登録
                const res = await axios.post("/api/todo", this.item);

                // リスト再構築
                this.createDispInfo(res.data);
                this.allItems.push(res.data);
                this.createItemList();

                // 入力項目をクリア
                this.clear();
            } catch (error) {
                this.errorProc(error);
            }
        },

        /**
         * 更新処理(update)
         */
        async update(targetItem) {
            try {
                // 締切日時を設定する
                targetItem.limit_at = this.getDate(
                    targetItem.limit_at_day,
                    targetItem.limit_at_time
                );

                // 完了日時を設定する
                targetItem.complete_at = targetItem.checked
                    ? new moment()
                    : null;

                // 更新処理
                await axios.put(`/api/todo/${targetItem.id}`, targetItem);

                // グループごとの項目リストを再生成する
                this.createDispInfo(targetItem);
                this.createItemList();
                this.clear();
            } catch (error) {
                this.errorProc(error);
            }
        },

        /**
         * 削除(delete)
         */
        async deleteItem(targetItem) {
            try {
                // 削除処理
                await axios.delete(`/api/todo/${targetItem.id}`);

                // 画面から削除
                this.allItems = this.allItems.filter(item => {
                    return item.id != targetItem.id;
                });

                // グループごとの項目リストを再生成する
                this.createItemList();
                this.clear();
            } catch (error) {
                this.errorProc(error);
            }
        },

        /**
         * 通信エラー処理
         */
        errorProc(error) {
            if (error.response) {
                if (error.response.status == 419) {
                    this.$store.dispatch("auth/clearUser");
                    alert(
                        "一定時間操作が行われなかったためログアウトしました。再度ログインしてください。"
                    );
                    this.$router.go({
                        name: "Login"
                    });
                }
            } else {
                alert(JSON.stringify(error.message));
            }
        },
        /**
         * 項目リスト作成
         */
        createItemList() {
            this.itemGroups = [];

            // 締め切りあり
            const limitArray = this.allItems.filter(item => {
                return item.limit_at != null;
            });
            this.itemGroups.push({
                title: "締め切りあり",
                sortColumn: "limit_at",
                headerColor: "#B71C1C",
                order: "asc",
                lists: limitArray
            });

            // 締め切りなし
            const noLimitArray = this.allItems.filter(item => {
                return item.limit_at == null;
            });
            this.itemGroups.push({
                title: "いつでも",
                headerColor: "#0D47A1",
                sortColumn: "created_at",
                order: "desc",
                lists: noLimitArray
            });
        },

        /**
         * 1件分の表示データを生成する
         */
        createDispInfo(item) {
            if (item.limit_at != null) {
                const now = moment();
                const limit = moment(item.limit_at);
                item.limit_at_day = limit.format("YYYY-MM-DD");
                item.limit_at_time = limit.format("HH:mm");
                item.isPastDate = limit.isBefore(now);
                item.isToday =
                    limit.format("YYYY-MM-DD") == now.format("YYYY-MM-DD");
            }
            item.checked = item.complete_at != null ? true : false;
        },

        /**
         * 日付・時間から日時文字列を返却
         */
        getDate(day, time) {
            // 日付・時刻ともに未設定
            if (day == null && time == null) {
                return null;
            }

            if (day != null && time != null) {
                // 両方設定あり
                return `${day} ${time}`;
            } else if (day != null) {
                // 日付のみ設定ありの場合、0時として扱う
                return `${day} 00:00`;
            }
            // 時刻のみ設定ありの場合、今日として扱う
            return `${moment().format("YYYY-MM-DD")} ${time}`;
        },

        /**
         * 項目クリア
         */
        clear() {
            this.item = {
                subject: "", // 項目名
                detail: "", // 詳細
                // category_id: -1, // カテゴリーID(TODO)
                limit_at: null, // 期限
                limit_at_day: null,
                limit_at_time: null,
                complete_at: null // 完了日時
            };
            this.isUpdate = false;
        },

        /**
         * 項目入力でCtrl+Enter押下時、簡易登録する
         */
        newEnter() {
            if (this.item.id != null) {
                this.update(this.item);
            } else {
                this.create();
            }
        },

        /**
         * エンターキー押下時にunfocusする
         */
        // unforcus() {
        //     if (event.keyCode !== 13) {
        //         return;
        //     }
        //     document.activeElement.blur();
        // },

        /**
         * リスト項目を編集対象として選択
         */
        edit(currItem) {
            this.item = currItem;
            this.isUpdate = true;
        },

        /**
         * 日付選択時の処理
         */
        selectDate(targetDate) {
            this.item.limit_at_day = targetDate;
        },

        /**
         * 時刻選択時の処理
         */
        selectTime(targetTime) {
            this.item.limit_at_time = targetTime;
        },

        /**
         * 再入力時にエラーをクリアする
         */
        clearError(itemName) {
            this.errors[itemName].hasError = false;
            this.errors[itemName].message = null;
        }
    }
};
</script>

<style>
.main-container {
    width: 500px;
    margin: auto;
}
</style>
