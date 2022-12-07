import Swal from "sweetalert2";
/**
 * /**
 * @file Axios Error Catcher With Sweetalert
 * @version 0.0.1
 * @author Berke Güleç <berke.ggulec@gmail.com>
 *
 * Axios Error Catcher usage example
 *  axios_request.post(URL).then((result) => {
 *    console.log(result);
 *  }).catch((err) => {
 *      axios_catcher(
 *          err,    //axios error obj
 *          (x)=>{} //optional
 *          ,true   //if param pass FALSE it returns only error object
 *      );
 *  });
 */
/**
 *
 * @param {Object} errObj
 * @param {Function} callBack
 * @param {Boolean} getMsg
 * @returns
 *
 */
export default (errObj, callBack, getMsg = true) => {
    if (typeof errObj.response !== "undefined") {
        let response = errObj.response;
        if (getMsg) {
            Swal.fire({
                icon: "error",
                text: response.data.msg,
            }).then((typeof callBack === "function" ? callBack(response.data) : null))
        }
        else return response;
    }
}
