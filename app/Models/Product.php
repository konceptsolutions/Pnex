<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'product_type_id', 'product_sub_type_id', 'price', 'bv', 'description', 'is_sold', 'updated_by'];

    //---------------------Storing user in autonet by his own bv-----------------------------------------
    public static function storeAutonetInfo($parent, $product_id, $sessionUserId, $percentage, $bv, $week_id, $autonets)
    {
        $distribute = 1;
        $sessionUserParent = User::find($sessionUserId);
        if ($sessionUserParent->reference_id != $parent->id) {
            if ($parent->is_free_user == 1) {
                $distribute = 0;
            }
        }
        if ($distribute == 1) {
            $total_bv = $bv * $percentage / 100;
            $bv_to_pkr = Setting::where('id', 1)->value('bv_to_pkr');
            $bv_to_pkr = $bv_to_pkr * $total_bv;
            $oldBal = UserLedger::where('user_id', $parent->id)->orderBy('id', 'desc')->first();
            $oldBal = $oldBal->balance ?? 0;
            $parentLedger = new UserLedger;
            $parentLedger->user_id = $parent->id;
            $parentLedger->product_id = $product_id;
            $parentLedger->network_user_id = $sessionUserId;
            $parentLedger->debit = 0;
            $parentLedger->credit = $bv_to_pkr;
            $parentLedger->balance = $oldBal - $bv_to_pkr;
            $parentLedger->bv = $total_bv;
            $parentLedger->week_id = $week_id;
            $parentLedger->save();
        }
        $parentBv = UserLedger::where([['week_id', $week_id], ['user_id', $parent->id]])->sum('bv');

        //-----Storing the parents of this user in autontes if this user reaches the network bv limit--------
        if ($parentBv >= $autonets[0]->network_bv) {
            self::storeAutonetUserByHisNetwork($parent->reference_id, $parentBv, $autonets, $week_id);
        }
        //----------------------------------if user reached  to autonet points-----------------
        $autonetUser = AutonetUser::where([['user_id', $parent->id], ['week_id', $week_id]])->get();

        //---------------------putting user in autonet 1----------------
        if ($parentBv >= $autonets[0]->bv  && $parentBv < $autonets[1]->bv) {
            if (count($autonetUser) == 0) {
                $AutonetUser = new AutonetUser;
                $AutonetUser->week_id = $week_id;
                $AutonetUser->user_id = $parent->id;
                $AutonetUser->autonet_id = $autonets[0]->id;
                $AutonetUser->save();
            }
        }

        //---------------------putting user in autonet 2----------------
        elseif ($parentBv >= $autonets[1]->bv  && $parentBv < $autonets[2]->bv) {
            if ($parent->is_free_user == 0) {

                //----checking that if user is in autonet 1 update it to autonet 2, if not then insert
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    if ($autonetUser[0]->autonet_id != $autonets[2]->id) {
                        $autonetUser[0]->autonet_id = $autonets[1]->id;
                        $autonetUser[0]->save();
                    }
                }
            }
        }

        //---------------------putting user in autonet 3----------------

        elseif ($parentBv >= $autonets[2]->bv  && $parentBv < $autonets[0]->bv +  $autonets[2]->bv) {
            if ($parent->is_free_user == 0) {
                //----checking that if user is in autonet 1 or 2 update it to autonet 3, if not then insert
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();
                }
            }
        }

        //---------------------user is already in autonet 3 now putting user in autonet 1 also----------------
        elseif ($parentBv >= $autonets[0]->bv +  $autonets[2]->bv  && $parentBv <  $autonets[1]->bv +  $autonets[2]->bv) {
            if ($parent->is_free_user == 0) {
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();
                }
            }
        }

        //---------------------user is already in autonet 3 now putting user in autonet 2 also----------------
        elseif ($parentBv >= $autonets[1]->bv +  $autonets[2]->bv  && $parentBv < $autonets[0]->bv + $autonets[1]->bv +  $autonets[2]->bv) {
            if ($parent->is_free_user == 0) {
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 2) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $autonetUser[1]->autonet_id = $autonets[1]->id;
                    $autonetUser[1]->save();
                }
            }
        }
        //---------------------user is already in autonet 3 and 2 now putting user in autonet 1 also----------------
        elseif ($parentBv >= $autonets[0]->bv + $autonets[1]->bv +  $autonets[2]->bv) {
            if ($parent->is_free_user == 0) {
                if (count($autonetUser) == 0) {

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {

                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 2) {

                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $autonetUser[1]->autonet_id = $autonets[1]->id;
                    $autonetUser[1]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $parent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();
                }
            }
        }
    }


    //---------------------Storing user in autonet by his team bv-----------------------------------------
    public static function storeAutonetUserByHisNetwork($reference_id, $parentBv, $autonets, $week_id)
    {
        $grandParent1 = User::find($reference_id);
        if ($grandParent1) {
            self::storeAutonetUsers($grandParent1, $week_id, $autonets, $parentBv);
            $grandParent2 = User::find($grandParent1->reference_id);
            if ($grandParent2) {
                self::storeAutonetUsers($grandParent2, $week_id, $autonets, $parentBv);
                $grandParent3 = User::find($grandParent2->reference_id);
                if ($grandParent3) {
                    self::storeAutonetUsers($grandParent3, $week_id, $autonets, $parentBv);
                }
            }
        }
    }

    public static function storeAutonetUsers($grandParent, $week_id, $autonets, $parentBv)
    {
        $autonetUser = AutonetUser::where([['user_id', $grandParent->id], ['week_id', $week_id]])->get();
        //---------------------putting user in autonet 1----------------
        if ($parentBv >= $autonets[0]->network_bv  && $parentBv < $autonets[1]->network_bv) {
            if (count($autonetUser) == 0) {
                $AutonetUser = new AutonetUser;
                $AutonetUser->week_id = $week_id;
                $AutonetUser->user_id = $grandParent->id;
                $AutonetUser->autonet_id = $autonets[0]->id;
                $AutonetUser->save();
            }
        }

        //---------------------putting user in autonet 2----------------
        elseif ($parentBv >= $autonets[1]->network_bv  && $parentBv < $autonets[2]->network_bv) {
            if ($grandParent->is_free_user == 0) {

                //----checking that if user is in autonet 1 update it to autonet 2, if not then insert
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    if ($autonetUser[0]->autonet_id != $autonets[2]->id) {
                        $autonetUser[0]->autonet_id = $autonets[1]->id;
                        $autonetUser[0]->save();
                    }
                }
            }
        }

        //---------------------putting user in autonet 3----------------

        elseif ($parentBv >= $autonets[2]->network_bv  && $parentBv < $autonets[0]->network_bv +  $autonets[2]->network_bv) {
            if ($grandParent->is_free_user == 0) {
                //----checking that if user is in autonet 1 or 2 update it to autonet 3, if not then insert
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();
                }
            }
        }

        //---------------------user is already in autonet 3 now putting user in autonet 1 also----------------
        elseif ($parentBv >= $autonets[0]->network_bv +  $autonets[2]->network_bv  && $parentBv <  $autonets[1]->network_bv +  $autonets[2]->network_bv) {
            if ($grandParent->is_free_user == 0) {
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();
                }
            }
        }

        //---------------------user is already in autonet 3 now putting user in autonet 2 also----------------
        elseif ($parentBv >= $autonets[1]->network_bv +  $autonets[2]->network_bv  && $parentBv < $autonets[0]->network_bv + $autonets[1]->network_bv +  $autonets[2]->network_bv) {
            if ($grandParent->is_free_user == 0) {
                if (count($autonetUser) == 0) {
                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 2) {
                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $autonetUser[1]->autonet_id = $autonets[1]->id;
                    $autonetUser[1]->save();
                }
            }
        }
        //---------------------user is already in autonet 3 and 2 now putting user in autonet 1 also----------------
        elseif ($parentBv >= $autonets[0]->network_bv + $autonets[1]->network_bv +  $autonets[2]->network_bv) {
            if ($grandParent->is_free_user == 0) {
                if (count($autonetUser) == 0) {

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[2]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 1) {

                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[1]->id;
                    $AutonetUser->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();
                } elseif (count($autonetUser) == 2) {

                    $autonetUser[0]->autonet_id = $autonets[2]->id;
                    $autonetUser[0]->save();

                    $autonetUser[1]->autonet_id = $autonets[1]->id;
                    $autonetUser[1]->save();

                    $AutonetUser = new AutonetUser;
                    $AutonetUser->week_id = $week_id;
                    $AutonetUser->user_id = $grandParent->id;
                    $AutonetUser->autonet_id = $autonets[0]->id;
                    $AutonetUser->save();
                }
            }
        }
    }
}
