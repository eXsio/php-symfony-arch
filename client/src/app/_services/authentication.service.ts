import {Injectable} from '@angular/core';
import {Router} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {BehaviorSubject, Observable} from 'rxjs';
import {map} from 'rxjs/operators';

import {environment} from '@environments/environment';
import {User} from '@app/_models';

@Injectable({providedIn: 'root'})
export class AuthenticationService {
  private userSubject: BehaviorSubject<User> = null;
  public user: Observable<User> = null;

  constructor(
    private router: Router,
    private http: HttpClient
  ) {
    this.userSubject = new BehaviorSubject<User>(this.userValue);
    this.user = this.userSubject.asObservable();
    this.user.subscribe(user => {
      sessionStorage.setItem('user', JSON.stringify(user));
    });
  }

  public get userValue(): User {
    let user = this.userSubject != null ? this.userSubject.value: null;
    if (user === null) {

      const userStr = sessionStorage.getItem('user');
      if (userStr !== null) {
        user = JSON.parse(userStr);
        if(this.userSubject != null) {
          this.userSubject.next(user);
        }
      }
    }
    return user;
  }

  login(username: string, password: string) {
    return this.http.post<any>(`${environment.apiUrl}/api/login_check`, {username, password}, {withCredentials: true})
      .pipe(map(user => {
        this.userSubject.next(user);
        return user;
      }));
  }

  logout() {
    this.http.post<any>(`${environment.apiUrl}/api/logout/`, {}, {withCredentials: true}).subscribe();
    this.userSubject.next(null);
    this.router.navigate(['/']);
  }
}
