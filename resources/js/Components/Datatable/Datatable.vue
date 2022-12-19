<template>
    <div :class="wrapperClass" :style="wrapperStyle" :id="`vueTableContent`">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0 float-left">
                            {{ cardTitle }}
                            <i
                                class="fa fa-sync cursor-pointer"
                                :class="{ spinner: loading }"
                                @click="inComponentRefresh"
                            ></i>
                        </h3>
                        <div class="float-right">
                            <label class="mr-1">Veri limiti:</label>
                            <select @change="changeLimit($event)" id="vTable-length-changer">
                                <option
                                    v-for="(item, index) in avaibleLimits"
                                    :key="index"
                                    :value="item"
                                >{{ item }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <Rows
                            :current-data="currentData"
                            :table-id="tableId"
                            :table-heads="tableHeads"
                            @triggeredevent="emitTriggered"
                        />
                    </div>
                    <div class="card-footer py-4">
                        <div class="row">
                            <div class="col">
                                <span>{{ showingDataText }}</span>
                            </div>
                            <div class="col">
                                <pagination
                                    :total-pages="totalPage"
                                    :total="totalData"
                                    :per-page="limit"
                                    :current-page="currentPage"
                                    @pagechanged="onPageChange"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="loading" class="card-overlay">
            <i style="font-size: 3rem; color: #18a4d1" class="icon-spinner9 spinner mr-2"></i>
        </div>
    </div>
</template>

<script setup>
import Pagination from "./Pagination.vue";
import { uuid } from "vue-uuid"; // uuid object is also exported to things
import { ref } from '@vue/reactivity';
import { computed, watch } from '@vue/runtime-core';
import { axios_request } from "../../Utils/axios";
import Rows from "./Rows.vue";
const emit = defineEmits(["tableRendered", "datachanged", "eventTriggered"]);
const props = defineProps({
    filter: Object,
    requestURL: String,
    method: String,
    refresh: Boolean,
    dataContent: Array,
    headClass: String,
    headStyle: String,
    wrapperClass: String,
    wrapperStyle: String,
    cardTitle: String,
    itemId: {
        type: String,
        default: "id"
    },
});

const currentData = ref([]);
const serializedExtract = ref({});
const currentPage = ref(1);
const limit = ref(10);
const totalPage = ref(0);
const renderedData = ref(0);
const avaibleLimits = ref([10, 20, 50, 100]);
const totalData = ref(0);
const showingDataText = ref("");
const tableId = ref(0);
const loading = ref(false);

//methods

const generateId = () => {
    let uniq = "vT_" + uuid.v1();
    tableId.value = uniq;
    emit("tableRendered", uniq);
};
const fireDataChanged = () => {
    emit("datachanged", currentData.value);
};
const changeLimit = (event) => {
    limit.value = Number(event.target.value);
    currentPage.value = 1;
    getData();
};
const onPageChange = (page) => {
    currentPage.value = page;
    getData();
};
const serializeExtractedData = () => {
    props.dataContent.forEach((element, index) => {
        serializedExtract.value[element.data] = index + 1;
    });
};
const getData = () => {
    loading.value = true;
    axios_request({
        method: props.method,
        url: props.requestURL,
        data: {
            limit: limit.value,
            currentPage: currentPage.value,
            externalFilter: props.filter,
        },
    }).then((result) => {
        let response = result.data;
        var tempRow = [];
        serializeExtractedData();
        response.data.forEach((ax) => {
            var tempColumn = [];
            Object.keys(ax).forEach((key) => {
                if (serializedExtract.value[key]) {
                    let tmpColumnData = {
                        name: key,
                        value: ax[key]
                    };
                    if (typeof props.dataContent[serializedExtract.value[key] - 1].render !== "undefined") {
                        tmpColumnData = { ...tmpColumnData, ...props.dataContent[serializedExtract.value[key] - 1].render(ax, ax[key], tempRow.length) };
                    }
                    tempColumn[serializedExtract.value[key] - 1] = tmpColumnData;


                }
                if (props.itemId && key == props.itemId) tempColumn[props.itemId] = ax[props.itemId];
            });
            tempRow.push(tempColumn);
        });
        currentData.value = tempRow;
        totalData.value = Number(response.totalData);
        totalPage.value = Math.ceil(totalData.value / limit.value);
        let calculateShowingResult = 0;
        renderedData.value = currentData.value.length;
        if (limit.value == renderedData.value)
            calculateShowingResult = renderedData.value * currentPage.value;
        else
            calculateShowingResult =
                limit.value * (currentPage.value - 1) + renderedData.value;
        renderedData.value = calculateShowingResult;
        showingDataText.value = getFilteredDataText.value;
        fireDataChanged();
        loading.value = false;
    }).catch((err) => { });
};

const emitTriggered = (args) => {
    emit("eventTriggered", args)
}

const tableHeads = computed(() => props.dataContent.map((e) => e.headText));

const getFilteredDataText = computed(() => {
    let startingVal = !renderedData.value ? 0 : (currentPage.value - 1) * limit.value + 1;
    let endingVal = renderedData.value;
    return `Toplam ${totalData.value} veriden ${startingVal} - ${endingVal} arası gösteriliyor.`;
});

watch(() => props.refresh, () => {
    if (props.refresh === true) currentPage.value = 1, getData();
})

const inComponentRefresh = () => {
    currentPage.value = 1, getData();
}

generateId();
if (props.requestURL) getData();
</script>
<style scoped>
@import "./datatable.css";
</style>
