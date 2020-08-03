<template>
    <v-menu
        v-model="showDatePicker"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="290px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="targetDate"
                label="締切日"
                append-icon="event"
                readonly
                v-bind="attrs"
                v-on="on"
                ref="limit_day"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="targetDate"
            locale="jp-ja"
            :day-format="(date) => new Date(date).getDate()"
            @input="selectDate"
        ></v-date-picker>
    </v-menu>
</template>

<script>
export default {
    components: {},
    props: {
        targetDate: {
            type: String,
        },
    },
    data() {
        return {
            showDatePicker: false,
            date: new Date().toISOString().substr(0, 10),
        };
    },
    methods: {
        /**
         * 日付選択時の処理
         */
        selectDate() {
            // カレンダーを非表示
            this.showDatePicker = false;

            // 親コンポーネントに選択日付を伝達
            this.$emit("selectDate", this.targetDate);
        },
    },
};
</script>
