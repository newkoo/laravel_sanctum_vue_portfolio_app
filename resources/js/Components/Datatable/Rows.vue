<template></template>

<script>
import { h } from 'vue';
const initializeNodes = (props, emit) => {
    function initializeTableHeads() {
        let serializedHeadElements = props.tableHeads.map((el, index) => h("th", { scope: "col", innerHTML: el }));
        return h("thead", { class: "thead-light" }, [
            h("tr", {}, serializedHeadElements)
        ]);
    }

    function initializeBodyElements() {

        const cellSanitizer = (cell) => {
            let cellNodes = [];

            if (typeof cell.nodes !== "undefined") {
                let isRaw = typeof cell.isRaw === "undefined" && cell.isRaw === true ? true : false;
                if (!isRaw) {
                    cellNodes = cell.nodes.map(e => {
                        let subnodeProps = {
                            innerHTML: undefined,
                            class: undefined,
                        };
                        if (e.innerHTML !== undefined) subnodeProps.innerHTML = e.innerHTML;
                        if (e.class !== undefined) subnodeProps.class = e.class;

                        if (e.trigger !== undefined) {
                            let triggerData = e.trigger;
                            if (triggerData.type == "click") {
                                subnodeProps.onClick = function (event) {
                                    let clickData = {
                                        name: triggerData.emitName,
                                        firstParam: triggerData.firstParam,
                                        secondParam: triggerData !== undefined ? triggerData.secondParam : null,
                                        event: event
                                    }
                                    emit("triggeredevent", clickData)
                                }
                            }
                        }

                        return h(
                            e.elementType,
                            subnodeProps,
                        )
                    });
                }
            }

            let nodeProps = {};
            if (!cellNodes.length) nodeProps.innerHTML = cell.value;

            return h(
                "td",
                nodeProps,
                cellNodes
            );
        }

        const initializeTableRow = (row) => h(
            "tr",
            {
                id: (typeof row['id'] !== "undefined" ? `vData-${row['id']}` : ``)
            },
            row.map(
                cell => cellSanitizer(cell)
            )
        )

        return h("tbody", {}, props.currentData.map((el, index) => initializeTableRow(el)));
    }

    return h("table", { class: 'table align-items-center table-flush', id: props.tableId }, [
        initializeTableHeads(),
        initializeBodyElements()
    ])
};
export default {
    emits: ['triggeredevent'],
    props: {
        tableHeads: {
            default: [],
            type: Array
        },
        currentData: {
            default: [],
            type: Array
        },
        tableId: String
    },
    setup(props, { emit }) {
        return () => initializeNodes(props, emit);
    },
}
</script>

<style scoped>
@import "./datatable.css";
</style>
