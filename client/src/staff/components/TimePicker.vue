<template>
    <v-menu
        ref="timePicker"
        v-model="showTimePicker"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="290px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="targetTime"
                label="時刻"
                append-icon="access_time"
                readonly
                v-bind="attrs"
                v-on="on"
            ></v-text-field>
        </template>
        <v-time-picker
            v-if="showTimePicker"
            v-model="targetTime"
            full-width
            @click:minute="selectTime"
        ></v-time-picker>
    </v-menu>
</template>

<script>
export default {
    components: {},
    props: {
        targetTime: {
            type: String,
        },
    },
    data() {
        return {
            // time: null,
            showTimePicker: false,
        };
    },
    methods: {
        /**
         * 時刻選択時の処理
         */
        selectTime() {
            // 選択ダイアログを非表示
            this.showTimePicker = false;

            // 親コンポーネントに選択時刻を伝達
            this.$emit("selectTime", this.targetTime);
        },
    },
};
</script>
