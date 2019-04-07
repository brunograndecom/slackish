import {getChannels, getMessages, sendMessage, createChannel, logout} from '../api/Chat';
import {
    SET_CHANNELS
} from './mutations';

export const SEND_NEW_MESSAGE = 'messages/send_new_message';
export const LIST_CHANNELS = 'channels/list_all_channels';
export const CREATE_NEW_CHANNEL = 'channels/create_new_channel';
export const LOGOUT = 'user/LOGOUT';

export default {
    [SEND_NEW_MESSAGE]({state}, message) {
        sendMessage(state.currentChannel, message);
        getMessages(state.currentChannel)
            .then((messages) => {
                state.messages = messages;
            });
    },
    [LIST_CHANNELS]({commit}) {
        getChannels()
            .then((channels) => {
                commit({
                    type: SET_CHANNELS,
                    channels,
                });
            });
    },
    [CREATE_NEW_CHANNEL](ctx, name) {
        createChannel(name);
    },
    [LOGOUT] () {
        return logout();
    },
}
