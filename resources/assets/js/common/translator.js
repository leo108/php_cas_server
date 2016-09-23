/**
 * Created by leo108 on 16/9/20.
 */

module.exports = (name) => {
    if (Laravel.lang[name]) {
        return Laravel.lang[name];
    }
    return name;
};