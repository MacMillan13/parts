import {
  MessageBody,
  SubscribeMessage,
  WebSocketGateway,
  WebSocketServer,
  WsResponse,
} from '@nestjs/websockets';
import { from, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Server, Socket } from 'socket.io';
import { ParserService } from './service/parser.service';

@WebSocketGateway({
  cors: {
    origin: '*',
  },
})
export class StoreParserGateway {
  constructor(private readonly parserService: ParserService) {}
  @WebSocketServer()
  server: Server;

  handleConnection(client: Socket) {
    console.log('Connected');
  }

  handleDisconnect(client: Socket) {
    console.log('Disconnected');
    // Handle disconnection event
  }

  @SubscribeMessage('events')
  findAll(@MessageBody() data: any): Observable<WsResponse<unknown>> {
    return this.parserService
      .startParsing(data)
      .pipe(map((item) => ({ event: 'events', data: item })));
  }
}
